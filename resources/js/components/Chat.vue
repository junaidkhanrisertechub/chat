<template>
	<div class="chat card">
		<div class="scrollable card-body" ref="hasScrolledToBottom">
	        <template v-for="message in messages">
	            <div class="message message-receive" v-if="user.id != message.user.id">

	                    <strong class="primary-font">
	                        {{ message.user.name }} :
	                    </strong>
                    <div v-if="message.message" class="text-message-container">
                        <p>
                            {{message.message}}
                        </p>
                    </div>
                        <img  width="100" height="100" v-if="message.image"  :src="'http://localhost/allo_chat/storage/app/'+message.image" alt="">
	            </div>
	            <div class="message message-send" v-else>
<!--	                    <strong class="primary-font">-->
<!--	                        {{ message.user.name }} :-->
<!--	                    </strong>-->
                    <div v-if="message.message" class="text-message-container">
                        <p>
                            {{message.message}}
                        </p>
                    </div>
                        <img width="100" height="100" v-if="message.image"  :src="'http://localhost/allo_chat/storage/app/'+message.image" alt="">
<!--                    <p>-->
<!--                        {{message.user.created_at}}-->
<!--                    </p>-->
	            </div>
	        </template>
	    </div>

	    <div class="chat-form input-group">
	        <input id="btn-input" type="text" name="message" class="form-control input-sm message-" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="addMessage">
            <span class="input-group-btn">
                <input type="file" name="attach_image" id="attach_image" ref="fileInput" style="display: none" @change="addMessageImage($refs.fileInput)">
                <i class="material-icons" @click="$refs.fileInput.click()">attach_file</i>
	            <button class="btn btn-primary" id="btn-chat" @click="addMessage">
	                Send
	            </button>
	        </span>
	    </div>

	</div>
</template>
<script>
	import { reactive, inject,ref, onMounted,onUpdated } from 'vue';
	import axios from 'axios';
	export default{
		props:['user'],
	    setup(props){
	    	let messages = ref([])
	    	let newMessage = ref('')
	    	let hasScrolledToBottom = ref('')

	    	onMounted(() =>{
	    		fetchMessages()
	    	})

	    	onUpdated(() => {
	    		scrollBottom()
	    	})

	    	Echo.private('chat-channel')
	          .listen('SendMessage', (e) => {
	            messages.value.push({
	              message: e.message.message,
	              image: e.message.image,
	              user: e.user
	            });
	        })



	    	const fetchMessages = async()=> {
	            axios.get('/messages').then(response => {
	                messages.value = response.data;
	            });
	        }
            const handleFileInputChange = (event) => {
                const file = event.target.files[0];
                // Store the file or perform any required processing
            };

            const addMessageImage = async (fileInput) => {
                if (!fileInput || !fileInput.files || !fileInput.files.length) {
                    // Handle the case where no file is selected
                    return;
                }
                // Retrieve the selected file from the file input
                const file = fileInput.files[0];

                // var file = $(this).prop("files")[0]; // Get the selected file
                var imagePath = URL.createObjectURL(file); // Create a temporary URL for the file

                // Create a FormData object to send the file and other data
                const formData = new FormData();
                formData.append('file', file);
                formData.append('message', newMessage.value);

                // Make a POST request to send the message and file data
                console.log(formData)
                try {
                    axios.post('/messages', formData).then(response => {
                        let user_message = {
                            user: props.user,
                            image:  response.data,
                            message: newMessage.value
                        };
                        messages.value.push(user_message);
                    });
                    // Handle the response or perform any additional actions
                } catch (error) {
                    // Handle any errors that occur during the request
                }

                // Reset the input values
                newMessage.value = '';
                fileInput.value = null;
            };

	        const addMessage = async()=> {
	        	let user_message = {
                    user: props.user,
                    image:  '',
                    message: newMessage.value
                };
	            messages.value.push(user_message);
	            axios.post('/messages', user_message).then(response => {
	              console.log(response.data);
	            });
                newMessage.value = ''
	        }

	        const scrollBottom = () =>{
	        	if(messages.value.length > 1){
		        	let el = hasScrolledToBottom.value;
	      			el.scrollTop = el.scrollHeight;
	        	}
	        }

	        return {
	        	messages,
	        	newMessage,
	        	addMessage,
                addMessageImage,
	        	fetchMessages,
                handleFileInputChange,
	        	hasScrolledToBottom
	        }
	    }
	}
</script>
