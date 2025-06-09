# Registra

Registra is a dynamic registration system developed as part of the **Web-Based Information Systems** course at our faculty. This project features a secure registration page that collects user details and stores them in a MySQL database.

## Project Overview

Registra includes:
- **User Personal Details:** Full name, username, phone, WhatsApp number, address, password, confirm password, user image, and email.
- **Client-Side Validations:** Ensures all fields are completed, validates the email and full name formats, and confirms that the password meets complexity requirements (minimum 8 characters, at least 1 number, and 1 special character) with a matching confirm password.
- **Server-Side Validations:** Checks for existing usernames via AJAX to prevent duplicate registrations.
- **Image Upload:** Enables users to upload a profile image, with the image file stored on the server and its name stored in the database.
- **WhatsApp Number Validation:** Provides a button to validate the WhatsApp number using the third-party MDBI API available at [RapidAPI: WhatsApp Number Validator](https://rapidapi.com/finestoreuk/api/whatsapp-numbervalidator3).

## Project Demo

Watch the project demonstration video: [Project Demo](https://drive.google.com/file/d/15bBE9DWbK6C_rQYHgPtYCcoW_Z7Snfpo/view?usp=drive_link)

## Project Structure

The project consists of the following PHP and JavaScript files:
- **Header.php:** Contains the header section.
- **Footer.php:** Contains the footer section.
- **Index.php:** The main page that includes both header and footer.
- **DB_Ops.php:** Houses database functions such as connection, insertion, selection, and server-side validations.
- **Upload.php:** Processes the uploaded user image.
- **API_Ops.php:** Contains the server-side code to connect to the WhatsApp API.
<!-- - **API_Ops.js:** JavaScript file that handles the client-side API calls and validation. -->

A backup of the database is also included to ensure data safety.

## Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **API Integration:** MDBI WhatsApp Number Validator via RapidAPI

## Setup & Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/YassenAli/Registra.git
   cd registra
   ```

2. **Configure the Database:**
   - Create a new MySQL database.
   - Import the provided database backup file to set up the required tables.
   - Update the database connection details in `DB_Ops.php`.

3. **Configure API Access:**
   - Sign up for the MDBI API on RapidAPI.
   - Update the API credentials in `API_Ops.php` or `API_Ops.js` accordingly.

4. **Run the Project:**
   - Deploy the project on a local or remote server with PHP and MySQL support.
   - Open `Index.php` in your web browser to access the registration page.

## Usage

- Complete the registration form with all required details.
- Client-side validations will prompt you to correct any errors before submission.
- On form submission, server-side validations (including AJAX checks for duplicate usernames) ensure data integrity.
- Use the WhatsApp validation button to verify the provided number through the integrated API.
- Once validated, your details and uploaded image will be stored in the database.

## Testing

- **Client-Side Testing:** Validate that all form fields are correctly checked for proper input.
- **Server-Side Testing:** Ensure that AJAX calls accurately detect existing usernames and that file uploads are processed securely.
- **API Testing:** Confirm that the WhatsApp number validation functions as expected.
