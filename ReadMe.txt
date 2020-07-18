Define my own dictionary and then use it to translate English texts in input. Project had central focus on back-end and front-end security to prevent attacks.

A web page that:
- Ensures a secure Session mechanism.
- Allows the user to submit a file (dictionary file) that contains English words of your choice and the direct translation in another language of your choice 

A web application that:
- Reads the file in input, interprets it and sends to the database the translation model.
- Reads the input from the text input and applies the translation based on the model.
- The "default model" should be used in case the User didn't sign in, or if it's logged in, by didn't upload any translation model.
If the User is logged in and has uploaded a translation model, this model must be applied by the web application.

A mySQL database that:
- Stores the information regarding the translation model per each user.
- Stores the information related to each user account with username and password, in the most secure way of your knowledge.
