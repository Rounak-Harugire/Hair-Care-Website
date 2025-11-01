# Hair-Care-Website
💇‍♀️ HairCare – Personalized Ayurvedic Hair Wellness Platform

HairCare is a full-stack web application built with PHP and MySQL, designed to provide users with personalized hair care recommendations based on their Ayurvedic dosha, hair profile, and lifestyle.
It combines traditional Ayurvedic wisdom with modern web technology to help users discover natural solutions for healthy, balanced hair.

🌿 Key Features
🧭 User Module

Personalized Hair Quiz – Step-by-step assessment across four categories:

Your Details

Dosha Profile

Hair Profile

Lifestyle Profile

Dynamic Quiz Flow – Auto-navigates between questions with “Next” buttons.

Personalized Recommendations – Generates customized tips and remedies based on quiz results.

User Dashboard – Displays user profile, quiz history, and saved recommendations.

Appointment Booking – Users can schedule consultations with hair experts directly through the platform.

🔐 Authentication System

Secure Login & Signup using PHP sessions.

User Profile Management with stored results and preferences in the MySQL database.

⚙️ Admin / Expert Module

Admin or specialists can log in to view user data, quiz outcomes, and appointments for follow-ups or research purposes.

🧩 System Architecture
Layer	Technology Used
Frontend	HTML5, CSS3, JavaScript
Backend	PHP (Procedural)
Database	MySQL (setup.sql included for schema setup)
Server	Apache / XAMPP / WAMP
Core Files	index.html, quiz.php, quiz_results.php, appointment.php, profile.php, dashboard.php, login.php, signup.php, db.php
📊 Database Overview

Key Tables (from setup.sql):

users – stores user credentials and personal info.

quiz_responses – logs user quiz answers.

results – stores computed dosha types and recommendations.

appointments – manages expert consultations.

💡 Workflow Summary

User registers or logs in.

Completes the multi-step hair quiz.

System analyzes responses and identifies dominant dosha type (Vata, Pitta, or Kapha).

Personalized results are generated with natural remedies and care routines.

User can book appointments or view history in the dashboard.

🌸 Features Snapshot

Responsive UI design.

Session-based secure authentication.

Data-driven recommendation system.

Appointment and consultation management.

Easy-to-deploy structure with setup.sql database script.

🔮 Future Enhancements

AI-based recommendation model using hair image input.

Integration of payment gateway for premium consultations.

Email notification system for appointments and follow-ups.

Admin analytics dashboard for user engagement tracking.

🧑‍💻 File Structure
WT/
│── index.html           # Landing Page
│── signup.php           # New user registration
│── login.php            # User authentication
│── dashboard.php        # User dashboard with results & actions
│── profile.php          # Manage user profile
│── quiz.php             # Multi-step hair quiz
│── quiz_results.php     # Personalized recommendation page
│── appointment.php      # Appointment booking system
│── landing.php          # HairCare overview page
│── db.php               # Database connection
│── setup.sql            # MySQL database schema
│── logout.php           # Session termination
│── error.log            # Error tracking

🧠 Core Concept

HairCare bridges Ayurvedic diagnostics and modern digital wellness.
By analyzing the user’s natural body type (dosha) and hair patterns, it offers science-backed, personalized care suggestions to maintain balance, prevent damage, and promote growth.

💬 Tagline

“Know Your Dosha, Nourish Your Roots — Naturally.”
