# Volunteer Matching System (VMS)




https://github.com/user-attachments/assets/fb58b2c7-c724-4564-b84f-0787404e783f




## Introduction

The **Volunteer Matching System (VMS)** is a web-based application developed to streamline the interaction between volunteers and organizations. VMS simplifies the process of connecting willing volunteers with meaningful opportunities posted by companies and organizations. Volunteers can browse opportunities and apply for positions, while organizations can post and manage applications, bridging the gap between social needs and community involvement.

### Problem Statement

The traditional process of volunteer matching is often manual and time-consuming, leading to missed opportunities and inefficient connections.

### Solution

The VMS automates volunteer recruitment and placement, using an advanced algorithm to match volunteers with opportunities based on their skills, interests, and availability.

## How It Works

Built using a client-server architecture, VMS utilizes structured data transfer via XML and integrates a MySQL database for persistent storage. The system offers a seamless user experience enhanced by JavaScript scripts and an API for location-based services.

### Key Features

1. **Registration**: Individuals and organizations create accounts on the platform.
2. **Profile Creation**: Users can build detailed profiles with information on interests, skills, and availability.
3. **Opportunity Posting**: Organizations post volunteer opportunities with specifics like role type, time commitment, and skills required.
4. **Opportunity recomendation**: Using your profile data, location, and availability, the platform ranks and displays volunteer opportunities, prioritizing those that best match your interests and capabilities.

## Benefits

- **Customized Matches**: Tailored matches based on preferences and skills.
- **Enhanced Volunteer Satisfaction**: Volunteers find roles suited to their interests.
- **Increased Organizational Impact**: Efficient recruitment and retention of volunteers improve an organization’s impact.
- **Data-Driven Insights**: Valuable data on volunteer activities allows for informed decision-making.

---

## System Requirements

- **Frontend**: HTML5, CSS3, JavaScript (AJAX, XMLHttpRequest)
- **Backend**: PHP, MySQL (using PDO for secure database access)
- **Data Transfer**: XML
- **API**: Google Maps API for location-based searches

---

## Architecture Overview

### Technology Stack

#### Client-Side
- **HTML5 & CSS3**: For building the user interface, including forms and dashboards.
- **JavaScript**: Manages asynchronous communication and dynamic interface updates.
- **XML**: Used for structured data transfer between the client and server.

#### Server-Side
- **PHP**: Handles server-side logic, including form processing, session management, and database interaction.
- **MySQL**: Manages persistent data for user accounts, opportunities, and applications.
- **PDO (PHP Data Objects)**: Ensures secure database interactions with prepared statements to prevent SQL injection.

#### API
- **Google Maps API**: Provides map-based search functionality, helping volunteers locate opportunities.

### Core Components

1. **Client-Server Interaction**
   - The client communicates with the server via AJAX, using XML data transfer for a smooth, asynchronous experience.
   - PHP scripts on the server process requests, fetch data from the database, and respond with XML data.

2. **Data Transfer Using XML**
   - XML is the primary format for structured data exchange, allowing organized and easily parsed data transfer between client and server.

3. **Database Integration**
   - VMS integrates with a MySQL database with a structured schema:
      - **`users`**: Stores user data, including name, email, and role (volunteer or company).
      - **`volunteer_postings`**: Stores information about opportunities posted by companies.
      - **`applications`**: Maps volunteer applications to specific opportunities.

4. **API Integration**
   - Google Maps API allows volunteers to view opportunity locations, with a "View Map" feature displaying coordinates on a map.

5. **Client-Side Scripting**
   - JavaScript handles various functions:
      - **AJAX Requests**: Sends and receives data from the server to update the interface.
      - **Form Validation**: Ensures correct data entry before form submission.
      - **XML Parsing**: Processes XML data for dynamic updates on the dashboard.

6. **Optional Socket Programming**
   - WebSockets could enhance VMS by allowing real-time updates, providing live notifications for new opportunities or application statuses.

---

## Database Schema

### Tables and Columns

1. **`users`**  
   Stores information about all registered users on the platform, including their profile details, contact preferences, and role-specific data.

   - `id`: Primary key, unique identifier for each user.
   - `name`: User's full name.
   - `email`: Unique email address.
   - `password`: User's hashed password.
   - `role`: User's role, either `volunteer` or `company`.
   - `created_at`: Timestamp of when the user account was created.
   - `location`: User's current location or city.
   - `skills`: List of the user's skills, stored as comma-separated values or a JSON array.
   - `interests`: List of the user's interests, stored as comma-separated values or a JSON array.
   - `availability`: User's availability for volunteer opportunities. Options include `weekdays`, `weekends`, `evenings`, or `anytime`.
   - `preferred_locations`: List of locations preferred by the user for volunteering, stored as a comma-separated string.
   - `receive_email_updates`: Boolean indicating if the user wants to receive email updates. Defaults to `TRUE`.
   - `receive_sms_updates`: Boolean indicating if the user wants to receive SMS updates. Defaults to `FALSE`.
   - `subscribe_newsletter`: Boolean indicating if the user is subscribed to the newsletter. Defaults to `TRUE`.
   - `profile_picture_url`: URL to the user's profile picture.
   - `updated_at`: Timestamp that records the last time the user's profile was updated. Updates automatically whenever profile information changes. 

2. **`volunteer_postings`**
   - `id`: Primary key, unique identifier for each posting.
   - `company_id`: Foreign key linking to the company in `users`.
   - `position`: Title of the volunteer position.
   - `date`: Scheduled date for the volunteer opportunity.
   - `location`: Location of the opportunity.
   - `description`: Details about the volunteer role.
   - `created_at`: Timestamp of posting creation.

3. **`applications`**
   - `id`: Primary key, unique identifier for each application.
   - `volunteer_id`: Foreign key linking to the volunteer in `users`.
   - `posting_id`: Foreign key linking to a posting in `volunteer_postings`.
   - `application_date`: Timestamp of application submission.
