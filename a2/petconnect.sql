-- PetConnect Database Schema
-- For localhost: database name = petconnect
-- For Jacob5: database name = s3976066 (already created by RMIT IT)
-- Student: Yizhao Zheng | s3976066

-- Run this on localhost only:
CREATE DATABASE IF NOT EXISTS petconnect
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE petconnect;

-- Pets table (exact schema as provided)
CREATE TABLE IF NOT EXISTS pets (
    pet_id          INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    species         ENUM('Dog','Cat','Bird','Rabbit','Other') NOT NULL DEFAULT 'Dog',
    breed           VARCHAR(100),
    age_years       INT,
    age_months      INT,
    gender          ENUM('Male','Female','Unknown') NOT NULL DEFAULT 'Unknown',
    size            ENUM('Small','Medium','Large','Extra Large') NOT NULL DEFAULT 'Medium',
    description     TEXT          NOT NULL,
    health_info     TEXT,
    image_path      VARCHAR(255),
    adoption_fee    DECIMAL(8,2)  NOT NULL DEFAULT 0.00,
    status          ENUM('Available','Pending','Adopted') NOT NULL DEFAULT 'Available',
    created_at      DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Sample data using AT1 image filenames (Buddy.jpg, Whiskers.jpg, etc.)
INSERT INTO pets
    (name, species, breed, age_years, age_months, gender, size, description, health_info, image_path, adoption_fee, status)
VALUES
    ('Buddy',    'Dog',    'Golden Retriever', 3, 6, 'Male',   'Large',       'Friendly and energetic golden retriever looking for an active family. Loves playing fetch and swimming.',            'Fully vaccinated, microchipped, neutered. No known health issues.',              'Buddy.jpg',    250.00, 'Available'),
    ('Whiskers', 'Cat',    'Tabby',            2, 0, 'Female', 'Small',       'Sweet and affectionate tabby cat. Great with children and other cats. Indoor cat preferred.',                        'Vaccinated, spayed, microchipped. Healthy.',                                    'Whiskers.jpg', 150.00, 'Available'),
    ('Max',      'Dog',    'Labrador Mix',     5, 0, 'Male',   'Large',       'Gentle giant who loves cuddles. Well-trained and house-broken. Perfect family dog.',                                 'Vaccinated, neutered, microchipped. Mild arthritis managed with supplements.',   'Max.jpg',      200.00, 'Available'),
    ('Luna',     'Cat',    'Siamese',          1, 8, 'Female', 'Medium',      'Playful and curious Siamese kitten. Very vocal and loves attention. Needs an interactive owner.',                    'Fully vaccinated, spayed, microchipped.',                                       'Luna.jpg',     180.00, 'Pending'),
    ('Charlie',  'Bird',   'Cockatiel',        2, 0, 'Male',   'Small',       'Hand-raised cockatiel who loves to whistle and interact. Comes with cage and toys.',                                 'Healthy, vet checked. Can mimic simple tunes.',                                 'Charlie.jpg',  120.00, 'Available'),
    ('Bella',    'Dog',    'Beagle',           4, 3, 'Female', 'Medium',      'Sweet beagle with excellent temperament. Good with kids and other dogs. Loves walks.',                               'Vaccinated, spayed, microchipped. Healthy.',                                    'Bella.jpg',    220.00, 'Available'),
    ('Oliver',   'Cat',    'Persian',          3, 0, 'Male',   'Medium',      'Laid-back Persian cat who enjoys quiet environments. Requires regular grooming.',                                    'Vaccinated, neutered, microchipped. Healthy coat.',                             'Oliver.jpg',   200.00, 'Available'),
    ('Rocky',    'Dog',    'German Shepherd',  6, 0, 'Male',   'Extra Large', 'Loyal and protective German Shepherd. Experienced owner preferred. Excellent guard dog.',                            'Vaccinated, neutered, microchipped. Hip dysplasia managed.',                    'Rocky.jpg',    180.00, 'Available');
