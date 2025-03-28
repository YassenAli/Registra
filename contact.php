<?php include 'header.php'; ?> 

<main class="contact-container">
    <section class="contact-info">
        <h2>Contact Us</h2>
        <p>Have any questions? Reach out to us!</p>

        <div class="contact-details">
            <p>📍 Address: 123 Registra Street, City, Country</p>
            <p>📞 Phone: +123 456 7890</p>
            <p>📧 Email: contact@registra.com</p>
        </div>
    </section>

    <section class="contact-form">
        <h3>Send Us a Message</h3>
        <form action="process_contact.php" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>

    <section class="contact-map">
        <h3>Find Us</h3>
        <iframe src="https://www.google.com/maps/embed?..." width="100%" height="300" style="border:0;"
            allowfullscreen="">
        </iframe>
    </section>
</main>

<?php include 'footer.php'; ?> 