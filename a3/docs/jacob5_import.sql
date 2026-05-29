-- users table
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `joined_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- pets table
CREATE TABLE `pets` (
  `pet_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL,
  `species` enum('Dog','Cat','Bird','Rabbit','Other') NOT NULL DEFAULT 'Dog',
  `breed` varchar(100) DEFAULT NULL,
  `age_years` int(11) DEFAULT NULL,
  `age_months` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Unknown') NOT NULL DEFAULT 'Unknown',
  `size` enum('Small','Medium','Large','Extra Large') NOT NULL DEFAULT 'Medium',
  `description` text NOT NULL,
  `health_info` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `adoption_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('Available','Pending','Adopted') NOT NULL DEFAULT 'Available',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pet_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed users (password = password123)
INSERT INTO users (username, email, password, phone, location) VALUES ('sarah_animal_lover', 'sarah@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-0101', 'Melbourne, VIC');
INSERT INTO users (username, email, password, phone, location) VALUES ('john_foster', 'john@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-0102', 'Sydney, NSW');
INSERT INTO users (username, email, password, phone, location) VALUES ('emma_petcare', 'emma@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-0103', 'Brisbane, QLD');
INSERT INTO users (username, email, password, phone, location) VALUES ('mike_rescuer', 'mike@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-0104', 'Perth, WA');
INSERT INTO users (username, email, password, phone, location) VALUES ('lisa_shelter', 'lisa@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-0105', 'Adelaide, SA');
INSERT INTO users (username, email, password, phone, location) VALUES ('test_user', 'test@example.com', '$2y$10$NRtTgVEm.IA.GXunqwaC5uuhGfQ84N0PoHfcAskhIaAd6LiLDXEk6', '555-9999', 'Melbourne');
INSERT INTO users (username, email, password, phone, location) VALUES ('ownertest_1779620940650', 'owner_1779620940676@test.com', '$2y$10$/wl3xq85E20wce9.CvTIGOYjLUO0Xkeo1uqUbkziB14jn.ovV.eFa', '', '');

-- Seed pets
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (1, 'Buddy', 'Dog', 'Golden Retriever', 3, 6, 'Male', 'Large', 'Friendly and energetic golden retriever looking for an active family. Loves playing fetch and swimming.', 'Fully vaccinated, microchipped, neutered. No known health issues.', '1.jpg', 250.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (1, 'Whiskers', 'Cat', 'Tabby', 2, 0, 'Female', 'Small', 'Sweet and affectionate tabby cat. Great with children and other cats. Indoor cat preferred.', 'Vaccinated, spayed, microchipped. Healthy.', '2.jpg', 150.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (2, 'Max', 'Dog', 'Labrador Mix', 5, 0, 'Male', 'Large', 'Gentle giant who loves cuddles. Well-trained and house-broken. Perfect family dog.', 'Vaccinated, neutered, microchipped. Mild arthritis managed with supplements.', '3.jpg', 200.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (2, 'Luna', 'Cat', 'Siamese', 1, 8, 'Female', 'Medium', 'Playful and curious Siamese kitten. Very vocal and loves attention. Needs an interactive owner.', 'Fully vaccinated, spayed, microchipped.', '4.jpg', 180.00, 'Pending');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (3, 'Charlie', 'Bird', 'Cockatiel', 2, 0, 'Male', 'Small', 'Hand-raised cockatiel who loves to whistle and interact. Comes with cage and toys.', 'Healthy, vet checked. Can mimic simple tunes.', '5.jpg', 120.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (3, 'Bella', 'Dog', 'Beagle', 4, 3, 'Female', 'Medium', 'Sweet beagle with excellent temperament. Good with kids and other dogs. Loves walks.', 'Vaccinated, spayed, microchipped. Healthy.', '6.jpg', 220.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (4, 'Oliver', 'Cat', 'Persian', 3, 0, 'Male', 'Medium', 'Laid-back Persian cat who enjoys quiet environments. Requires regular grooming.', 'Vaccinated, neutered, microchipped. Healthy coat.', '7.jpg', 200.00, 'Available');
INSERT INTO pets (user_id, name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status) VALUES (5, 'Rocky', 'Dog', 'German Shepherd', 6, 0, 'Male', 'Extra Large', 'Loyal and protective German Shepherd. Experienced owner preferred. Excellent guard dog.', 'Vaccinated, neutered, microchipped. Hip dysplasia managed.', '8.jpg', 180.00, 'Available');
