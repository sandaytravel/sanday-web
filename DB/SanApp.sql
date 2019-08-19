-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 14, 2019 at 12:14 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `SanApp`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `content`, `created_at`, `updated_at`) VALUES
(4, '<h2 class="t_main" style="margin: 0px 0px 32px; color: rgb(255, 87, 34); font-size: 36px; font-family: -apple-system, BlinkMacSystemFont, " segoe="" ui",="" roboto,="" "droid="" sans",="" "helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" text-align:="" center;"="">OUR INVESTORS</h2><p class="t20" style="margin-bottom: 0px; padding: 0px; font-size: 20px; line-height: 1.3; color: rgb(51, 51, 51); font-family: -apple-system, BlinkMacSystemFont, " segoe="" ui",="" roboto,="" "droid="" sans",="" "helvetica="" neue",="" helvetica,="" arial,="" sans-serif;="" text-align:="" center;"=""><b>We are proudly supported by some of the world’s biggest investment firms.</b></p><p><br></p>', '2019-01-08 11:00:05', '2019-01-08 11:01:47');

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `actual_price` decimal(14,2) DEFAULT NULL,
  `display_price` decimal(14,2) DEFAULT '0.00',
  `popular_destination` tinyint(1) DEFAULT '0',
  `popular_activity` tinyint(1) DEFAULT '0',
  `description` longtext,
  `is_package_options` tinyint(1) NOT NULL DEFAULT '0',
  `is_what_to_expect` tinyint(1) NOT NULL DEFAULT '0',
  `what_to_expect_description` longtext,
  `is_activity_information` tinyint(1) NOT NULL DEFAULT '0',
  `activity_information_description` longtext,
  `is_how_to_use` tinyint(1) NOT NULL DEFAULT '0',
  `how_to_use_description` longtext,
  `is_cancellation_policy` tinyint(1) NOT NULL DEFAULT '0',
  `cancellation_policy_description` longtext,
  `views_counter` int(11) NOT NULL DEFAULT '0',
  `popular_counter` int(11) NOT NULL DEFAULT '0',
  `admin_approve` int(11) DEFAULT NULL COMMENT '0 = Pending ,1 = Approve, 2 = Decline',
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `merchant_id`, `city_id`, `category_id`, `subcategory_id`, `title`, `subtitle`, `image`, `actual_price`, `display_price`, `popular_destination`, `popular_activity`, `description`, `is_package_options`, `is_what_to_expect`, `what_to_expect_description`, `is_activity_information`, `activity_information_description`, `is_how_to_use`, `how_to_use_description`, `is_cancellation_policy`, `cancellation_policy_description`, `views_counter`, `popular_counter`, `admin_approve`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 1, 1, 'Jetpack/Jetlev Flyer Experience in Goa', NULL, '1550039964-1546247104-008_SUNSET_-_BIRD\'S-EYE_VIEW.jpg', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"><p>Get an unforgettable experience in Goa by trying out the newest extreme watersport, Jetpack!</p><p>Experience an awesome view as you fly high over the Dona Paula area!</p><p>Be joined by experienced instructors who\'ll take you through basic maneuvers</p><p>Come home with amazing experience and photos to share with friends!</p><p>Experience the newest and most exhilarating rush out in Goa with Jetlev Flyer a must do for the adventurous souls</p><p>Test</p></div>', 0, 1, '<div style="font-family:Airbnb Cereal"><p>Love doing water sports and want to try something different from surfing? In Goa, you can hydrofly up into the air with this one-of-a-kind Flyboard experience! Learn from a one-on-one session with a certified and experienced trainer and you’ll be soaring over Chopdem Jetty in no time. While Flyboarding, you can also take in beautiful views high up in the air. Make sure to bring a camera because you’re sure to want to capture this memorable experience and take amazing Flyboard shots that you can brag about to your loved ones. This is definitely a must-do in Goa for those who love adventures and sports!</p><p><br></p><p>Flyboard tricks</p><p><br></p><p>Flyboarding is a whole new experience that is exciting for thrill and adventure-seekers</p><p><br></p><p>Flyboard sunset</p><p>Enjoy the beautiful Indian beach and sunset while riding your Flyboard</p><p><br></p><p>Flyboard photography</p><p>Take stunning and awesome stunt photos you can share with your family and friends</p><p>jetlev flyer</p><p>Experience the thrill of trying the new Jetlev flyer</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p>Confirmation:</p><p>You will receive a confirmation email and voucher instantly after booking</p><p>In the event that you do not receive an email from us, please check your Spam folder or notify us via email</p><p>Additional Information:</p><p>Minimum required participation age for this activity is 14 years due to safety concerns</p><p>Swimwear, extra t-shirts, nylon shorts, sunglasses, sunscreen, hats, scarves, and cameras are recommended to be brought</p><p>Jetpack is fit for anyone in a good physical condition. The activity involves balancing and free movement</p><p>This activity is not suitable for pregnant women and those with heart/respiratory/sinus problems, as well as people with ear infections, acrophobia, and claustrophobia</p><p>All participants must disclose any prior or existing medical conditions that may be aggravated by, or may prevent them from undertaking these activities. They must be in good health with no back or neck injuries</p><p>Please note: In case of cancelation due to inclement weather, you may choose to reschedule or get a full refund</p><p>Test</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p>You can present either a printed or a mobile voucher for this activity</p><p>The voucher is valid only on the tour date and time specified</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p><br></p><p>Full refunds will be issued for cancelations made at least 72 hours prior to the activity</p></div>', 0, 0, 1, 'Active', 0, '2019-02-14 17:40:07', '2019-02-14 17:40:07'),
(2, 1, 3, 1, 1, 'Morning Tour To Bondla Wildlife Sanctuary', 'Explore Goa\'s most popular wildlife sanctuary!', '1550050872-ezgif.com-webp-to-png.png', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"><p>Go on a bird watching trip at the famous Bondla Wildlife Sanctuary</p><p>Enjoy a scenic drive on a forested uphill road with an unforgettable color palette</p><p>Witness rare bird species like the Malabar Grey Hornbills, Ruby Throat Bulbuls, Indian Pittas and more</p><p>Enjoy beautifully laid out gardens and a variety of flora along the route and take plenty of souvenir photos</p><p>Learn fun informative facts about Bondla Wildlife Sanctuary\'s inhabitants</p></div>', 0, 1, '<div style="font-family:Airbnb Cereal"><p>A trip to Bondla, Goa\'s smallest wildlife sanctuary, is a true treat for bird-watchers. The ride to the destination may be long but the road is incredibly picturesque. And, once you do get there, you have an extremely rare chance to see some of India’s most elusive and solitary bird species like the Ruby Throated Bulbul, the White Rumped Shama, the Fairy Blue Bird or the Malabar Grey Hornbill. As this is a private tour, you will have loads of time to take in Bondla’s beautiful scenery, both on the spot and on the road. You will travel in a comfortable air-conditioned vehicle with plenty opportunities to take photos along the way.</p><p><br></p><p> Bondla, Bondla sanctuary, Bondla bird watching, India bird watching</p><p><img src="https://res.klook.com/images/fl_lossy.progressive,q_65/c_fill,w_1295,h_863,f_auto/w_80,x_15,y_15,g_south_west,l_klook_water/activities/khzbiybzqujxsow8ivc9/MorningTourToBondlaWildlifeSanctuary.webp" style="width: 1178px;"><br></p><p>See India\'s rarest bird species at Bondla Wildlife Sanctuary</p><p><img src="https://res.klook.com/images/fl_lossy.progressive,q_65/c_fill,w_1295,h_1942,f_auto/w_80,x_15,y_15,g_south_west,l_klook_water/activities/yy1gir8dieb6xrkpyd6j/MorningTourToBondlaWildlifeSanctuary.webp" style="width: 1178px;"><br></p><p>Bondla, Bondla sanctuary, Bondla bird watching, India bird watching</p><p>Grab your camera for a rare chance to see elusive birds in their natural habitat</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p>Confirmation:</p><p>You will receive confirmation of your booking\'s availability within 1 business day. Once confirmed, we will send you the voucher via email</p><p>In the event that you do not receive an email from us, please check your Spam folder or notify us via email</p><p>Itinerary:</p><p>6:30am meet up outside Baskin Robbins ice cream shop at Calangute / pick up from hotel in Calangute area</p><p>Head towards Bondla Wildlife Sanctuary</p><p>Range around the forest area to witness rare species of birds and take photos</p><p>11:45am conclude the tour at Baskin Robbins in Calangute / drop off at hotel in Calangute area</p><p>Additional Information:</p><p>Children under the age of 3 can join the tour for free provided they do not occupy an additional seat</p><p>Hotel pick up and drop off services are available for hotels located in Calangute area or within 10 kms from Calangute only. Extra charges to be paid for pick up or drop outside specified limits</p><p>Casual and comfortable clothing, preferably full length pants and shoes are recommended</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p>You can present either a printed or a mobile voucher for this activity</p><p>The tour is valid only on the date and time specified</p><p>After booking, the operator will be in contact with you to reconfirm the pick up time and itinerary</p><p>Hotel pick up and drop off is available only within Calangute area or within 10 km from Calangute only</p></div>', 1, '<div style="font-family:Airbnb Cereal"><p>Full refunds will be issued for cancelations made at least 7 days prior to the activity</p></div>', 0, 0, 1, 'Active', 0, '2019-02-14 14:55:42', '2019-02-14 14:55:42'),
(3, 74, 5, 1, 1, 'Maxwell Hill in Taiping', 'As well as being the least developed of the stations, Maxwell Hill also claims to be oldest, having been founded in 1884.', '1550061654-03.jpg', NULL, NULL, NULL, 1, '<div style="font-family:airbnbcereal"><p>Located at 1,250 <gwmw class="ginger-module-highlighter-mistake-type-1" id="gwmw-15500663864739658647874">metres</gwmw>, Maxwell is a delightful, albeit extremely rainy, escape from the modern world. A crucial factor in both its lack of crowds and overall calm atmosphere is a ban on private cars accessing the hill. The choice is between a three to five hour walk, or a hair-raising 30-minute ride in a government jeep. Maxwell is about 10 <gwmw class="ginger-module-highlighter-mistake-type-1" id="gwmw-15500663871787020768111">kilometres</gwmw> from Taiping, in the northern state of Perak.<gwmw style="display:none;"></gwmw><gwmw style="display:none;"></gwmw></p></div>', 0, 1, '<div style="font-family:airbnbcereal"><p>Taiping is a city about 1 1/2 hr north of Ipoh, about half way between Ipoh and Penang. It is a small city and is renowned as being the wettest city in Malaysia with the highest rainfall. We visited Taiping a month ago and took the time to stay up on Maxwell Hill or locally called Bukit Larut.</p></div>', 1, '<div style="font-family:airbnbcereal"><p>Bukit Larut, formerly known as Maxwell Hill, is a popular highland destination in Malaysia. Located at an altitude of 1,250 m above sea level approximately 10 km from Taiping town in Perak; it is the wettest area in the country which experiences the highest rainfall. Bukit Larut is also home to the oldest hill station in Malaysia, built by William Edward Maxwell, a British Assistant Resident in Perak, in 1884 to provide a cool retreat for the colonial officials. In 1910, the hill resort was gazetted as a permanent forest reserve.</p></div>', 1, '<div style="font-family:airbnbcereal"><p>30 min by jeep for a height of 1036m.</p><p><br></p><p>3 to 4 hours of hiking is expected.</p><p><br></p><p>You may go ever slower than expected due to the fact that you need to give way to the frequent passing by jeeps.</p></div>', 1, '<div style="font-family:airbnbcereal"><p>Please read these Terms carefully before using the Website, which is an online marketplace. For the purpose of clarity, it is hereby stated that the Website is not engaged in providing any services by itself but is a platform, which merely connects the bus operators with the bus travellers.</p><p><br></p><p>These Terms are an electronic record in terms of the applicable legislation. This electronic record is generated by a computer system and does not require any physical or digital signatures. By using the Website, you signify your agreement to be bound by these Terms.</p></div>', 0, 0, 1, 'Active', 0, '2019-02-14 16:45:57', '2019-02-14 16:45:57'),
(4, 74, 2, 1, 1, 'adventure tracking', 'tracking', '1550137900-IMG_144869.jpg', NULL, NULL, NULL, 1, '<div style="font-family:Airbnb Cereal"></div>', 0, 0, '', 0, '', 0, '', 0, '', 0, 0, 1, 'Active', 1, '2019-02-14 15:24:44', '2019-02-14 15:24:44'),
(5, 76, 2, 2, 4, 'Adventure', 'Tracking', '1550142134-IMG_144869.jpg', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"></div>', 0, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 'Active', 1, '2019-02-14 16:32:53', '2019-02-14 16:32:53'),
(6, 76, 2, 2, 4, 'Adventure tracking', 'tracking', '1550142889-IMG_144869.jpg', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"></div>', 0, 0, '', 0, '', 0, '', 0, '', 0, 0, 1, 'Active', 1, '2019-02-14 16:58:05', '2019-02-14 16:58:05'),
(7, 80, 2, 2, 4, 'Adventure Tracking', 'Tracking', '1550144378-IMG_144869.jpg', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"></div>', 0, 0, '', 0, '', 0, '', 0, '', 0, 0, 0, 'Active', 1, '2019-02-14 17:09:41', '2019-02-14 17:09:41'),
(8, 82, 2, 2, 4, 'Adventure Tracking', 'Tracking', '1550144981-IMG_144869.jpg', NULL, NULL, NULL, NULL, '<div style="font-family:Airbnb Cereal"></div>', 0, 0, '', 0, '', 0, '', 0, '', 0, 0, 1, 'Active', 0, '2019-02-14 17:36:36', '2019-02-14 17:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `activity_faqs`
--

CREATE TABLE `activity_faqs` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_faqs`
--

INSERT INTO `activity_faqs` (`id`, `activity_id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(52, 3, 'how long to take if hiking up to Bukit Larut? thanks', 'Give it a good 1 hour per way..using the road..if u hike through the forest trail i think will take longer and not advisable if it rains.', '2019-02-14 16:45:57', '2019-02-14 16:45:57'),
(53, 3, 'The jungle trail is a 1km walk of almost consistence upslope, which takes approximately 30-45mins to complete.', 'As there are quite a number of trail diversion, numbered checkpoints (10 in total) are marked on trees. Follow the numbers to ensure that you are on the right trail. Upon reaching the access road, walk another 400 meters up the slope to a rest shelter which the locals call it “the third station.” (GPS: 4.865910, 100.767420, turn 29)', '2019-02-14 16:45:57', '2019-02-14 16:45:57'),
(54, 3, 'Hello', 'Hii', '2019-02-14 16:45:57', '2019-02-14 16:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `activity_package_options`
--

CREATE TABLE `activity_package_options` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `package_title` varchar(255) NOT NULL,
  `actual_price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `display_price` decimal(14,2) DEFAULT '0.00',
  `description` longtext,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `validity` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_package_options`
--

INSERT INTO `activity_package_options` (`id`, `activity_id`, `package_title`, `actual_price`, `display_price`, `description`, `is_delete`, `validity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jetlev Flyer Experience', '2000.00', '1850.00', '<p>Inclusive Of:</p><p>25 minutes flying</p><p>Safety Instruction</p><p>Training by instructor</p><p>Flying with instructor</p><p>Wetsuit</p><p>Safety gear</p><p>Not Inclusive Of:</p><p>Hotel pick up and drop off</p><p>Tips and gratuities (optional)</p><p>Insurance</p>', 0, '2019-05-28', '2019-02-13 12:18:00', '2019-02-13 14:59:53'),
(2, 1, 'Jetpack Experience', '3200.00', '3000.00', '<p>Inclusive Of:</p><p>15 minutes jetpack experience</p><p>Pre training by instructor</p><p>Complimentary photos</p><p>All equipments needed</p><p>Safety gears and life jacket</p><p>Qualified and experienced trainers</p><p>Bottled drinking water</p><p>Not Inclusive Of:</p><p>Hotel pick up and drop off</p><p>Meals and beverages</p><p>Tips and gratuities (optional)</p>', 1, '2019-08-12', '2019-02-13 12:19:38', '2019-02-13 14:59:53'),
(3, 2, 'Private Tour', '2900.00', '2750.00', NULL, 0, '2019-02-15', '2019-02-13 15:12:56', '2019-02-14 12:17:21'),
(4, 3, 'Family Toor', '70000.00', '59000.00', NULL, 0, '2019-02-15', '2019-02-13 18:46:55', '2019-02-13 18:53:46'),
(5, 3, 'Family Toor', '70000.00', '59000.00', NULL, 1, '2019-02-15', '2019-02-13 18:51:56', '2019-02-13 18:52:24'),
(6, 3, 'Couple Toor', '40000.00', '34000.00', NULL, 0, '2019-02-20', '2019-02-13 18:51:56', '2019-02-13 18:53:46'),
(7, 4, 'premium', '1000.00', '1000.00', '', 0, '2019-02-28', '2019-02-14 15:24:27', '2019-02-14 15:24:27'),
(8, 5, 'pre', '1000.00', '1000.00', '', 0, '2019-02-27', '2019-02-14 16:33:18', '2019-02-14 16:33:18'),
(9, 6, 'Pre', '1000.00', '1000.00', NULL, 0, '2019-02-21', '2019-02-14 16:45:57', '2019-02-14 16:57:54'),
(10, 8, 'Pre', '1000.00', '1000.00', '', 0, '2019-02-28', '2019-02-14 17:20:43', '2019-02-14 17:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `activity_package_quantity`
--

CREATE TABLE `activity_package_quantity` (
  `id` int(11) NOT NULL,
  `activity_package_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `minimum_quantity` int(11) NOT NULL DEFAULT '1',
  `maximum_quantity` int(11) DEFAULT NULL,
  `actual_price` decimal(14,2) NOT NULL DEFAULT '0.00',
  `display_price` decimal(14,2) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_package_quantity`
--

INSERT INTO `activity_package_quantity` (`id`, `activity_package_id`, `name`, `minimum_quantity`, `maximum_quantity`, `actual_price`, `display_price`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'Adult', 1, NULL, '2000.00', '1850.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(2, 2, 'Adult', 1, NULL, '3200.00', '3000.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(3, 2, 'Child', 1, NULL, '2800.00', '2850.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(4, 2, 'Senior', 0, NULL, '2000.00', '1800.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(5, 1, 'Child', 0, NULL, '1800.00', '1850.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(6, 1, 'Senior', 0, NULL, '1500.00', '1400.00', 0, '2019-02-13 14:59:53', '2019-02-13 14:59:53'),
(7, 3, 'First Two Persons', 0, NULL, '2900.00', '2750.00', 0, '2019-02-14 12:17:21', '2019-02-14 12:17:21'),
(8, 3, 'Third Person Onwards', 0, NULL, '1200.00', '1200.00', 0, '2019-02-14 12:17:21', '2019-02-14 12:17:21'),
(9, 4, 'Adult, Child', 2, 4, '20000.00', '18000.00', 0, '2019-02-13 18:53:46', '2019-02-13 18:53:46'),
(10, 5, 'Adult, Child', 2, 4, '20000.00', '18000.00', 0, '2019-02-13 18:52:24', '2019-02-13 18:52:24'),
(11, 6, 'Adult', 2, 8, '20000.00', '16000.00', 0, '2019-02-13 18:53:46', '2019-02-13 18:53:46'),
(12, 7, 'adult', 1, NULL, '1000.00', NULL, 0, '2019-02-14 15:24:27', '2019-02-14 15:24:27'),
(13, 8, 'adult', 1, NULL, '1000.00', NULL, 0, '2019-02-14 16:33:18', '2019-02-14 16:33:18'),
(14, 9, 'Adult', 1, NULL, '1000.00', NULL, 0, '2019-02-14 16:57:54', '2019-02-14 16:57:54'),
(15, 10, 'Adult', 1, NULL, '1000.00', NULL, 0, '2019-02-14 17:20:43', '2019-02-14 17:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `activity_policies`
--

CREATE TABLE `activity_policies` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activity_policies`
--

INSERT INTO `activity_policies` (`id`, `activity_id`, `policy_id`, `created_at`, `updated_at`) VALUES
(87, 2, 3, '2019-02-14 14:55:42', '2019-02-14 14:55:42'),
(88, 2, 4, '2019-02-14 14:55:42', '2019-02-14 14:55:42'),
(101, 3, 3, '2019-02-14 16:45:57', '2019-02-14 16:45:57'),
(102, 3, 4, '2019-02-14 16:45:57', '2019-02-14 16:45:57'),
(109, 1, 3, '2019-02-14 17:40:07', '2019-02-14 17:40:07'),
(110, 1, 4, '2019-02-14 17:40:07', '2019-02-14 17:40:07'),
(111, 1, 5, '2019-02-14 17:40:07', '2019-02-14 17:40:07');

-- --------------------------------------------------------

--
-- Table structure for table `activity_reviews`
--

CREATE TABLE `activity_reviews` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_quantity_id` int(11) NOT NULL,
  `booking_date` date DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `activity_id`, `package_id`, `package_quantity_id`, `booking_date`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 49, 2, 3, 7, '2019-02-14', 1, '2019-02-13 17:45:27', '2019-02-14 12:20:09'),
(2, 49, 2, 3, 8, '2019-02-14', 0, '2019-02-13 17:45:27', '2019-02-14 12:20:09'),
(3, 49, 1, 1, 1, '2019-02-15', 1, '2019-02-13 17:45:45', '2019-02-13 17:45:45'),
(4, 49, 1, 1, 5, '2019-02-15', 1, '2019-02-13 17:45:45', '2019-02-13 17:45:45'),
(5, 49, 1, 1, 6, '2019-02-15', 1, '2019-02-13 17:45:45', '2019-02-13 17:45:45'),
(6, 49, 1, 2, 2, '2019-02-16', 1, '2019-02-13 17:45:55', '2019-02-13 17:45:55'),
(7, 49, 1, 2, 3, '2019-02-16', 1, '2019-02-13 17:45:55', '2019-02-13 17:45:55'),
(8, 49, 1, 2, 4, '2019-02-16', 1, '2019-02-13 17:45:55', '2019-02-13 17:45:55');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `icon`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Attraction & Shows', '1550038897-1546946894-k-purple.jpg', 'Active', 0, '2019-02-13 11:51:37', '2019-02-13 11:51:37'),
(2, 'Activities & Experience', '1550038930-1546581895-video-256x256.png', 'Active', 0, '2019-02-13 11:52:10', '2019-02-13 11:52:10'),
(3, 'Tours & Sightseeing', '1550143623-1546946928-2366034_image2_1.jpg', 'Active', 0, '2019-02-14 16:57:03', '2019-02-14 16:57:03'),
(4, 'Transport & WiFi', '1550143648-1546602298-video-256x256.png', 'Active', 0, '2019-02-14 16:57:28', '2019-02-14 16:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `timezone` varchar(255) DEFAULT NULL,
  `zone_name` varchar(255) DEFAULT NULL,
  `popular_destination` int(11) DEFAULT NULL COMMENT '0 = Normal, 1 = Popular',
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `country_id`, `city`, `image`, `description`, `timezone`, `zone_name`, `popular_destination`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'Benglore', '1550038557-1546248378-123.jpg', 'Benglore is most popular city in india', '(GMT +05:30)', 'Asia/Kolkata', NULL, 'Active', 0, '2019-02-13 11:45:58', '2019-02-13 11:45:58'),
(2, 1, 'Lavasa', '1550038808-1546248176-queensland_hero.jpg', 'Lavasa is hot destination forever.', '(GMT +05:30)', 'Asia/Kolkata', NULL, 'Active', 0, '2019-02-13 12:01:21', '2019-02-13 12:01:21'),
(3, 1, 'Goa', '1550039690-1546247104-008_SUNSET_-_BIRD\'S-EYE_VIEW.jpg', 'Goa is popular city', '(GMT +05:30)', 'Asia/Kolkata', NULL, 'Active', 0, '2019-02-13 12:04:51', '2019-02-13 12:04:51'),
(4, 1, 'Chennai', '1550040924-1546248507-b2ap3_large_Snorkelling-in-Kota-Kinabalu.jpg', 'Chennai is popular city forever', '(GMT +05:30)', 'Asia/Kolkata', 1, 'Active', 0, '2019-02-13 12:25:24', '2019-02-13 12:25:24'),
(5, 4, 'kuala lumpur', '1550060656-kuala-lumpur.jpg', 'Malaysia also has modern takes on the hill station concept, such as Genting Highlands (Resorts World Genting), and Berjaya Hills (Bukit Tinggi).', '(GMT +08:00)', 'Asia/Hong_Kong', 1, 'Active', 0, '2019-02-13 17:54:16', '2019-02-13 17:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `city_categories`
--

CREATE TABLE `city_categories` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `city_categories`
--

INSERT INTO `city_categories` (`id`, `city_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2019-02-13 12:01:21', '2019-02-13 12:01:21'),
(2, 2, 2, '2019-02-13 12:01:21', '2019-02-13 12:01:21'),
(3, 3, 1, '2019-02-13 12:04:51', '2019-02-13 12:04:51'),
(4, 3, 2, '2019-02-13 12:04:51', '2019-02-13 12:04:51'),
(5, 4, 1, '2019-02-13 12:25:24', '2019-02-13 12:25:24'),
(6, 5, 1, '2019-02-13 17:54:16', '2019-02-13 17:54:16'),
(7, 5, 2, '2019-02-13 17:54:16', '2019-02-13 17:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `continents`
--

CREATE TABLE `continents` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `continents`
--

INSERT INTO `continents` (`id`, `name`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'East Asia', 'Active', 0, '2019-02-13 11:40:21', '2019-02-13 11:40:21'),
(2, 'North America', 'Active', 0, '2019-02-13 11:40:21', '2019-02-13 11:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `continent_id` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `continent_id`, `country`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'India', 'Active', 0, '2019-02-13 11:40:53', '2019-02-13 11:40:53'),
(2, 1, 'Bangladesh', 'Active', 0, '2019-02-13 11:40:53', '2019-02-13 11:40:53'),
(3, 2, 'Malaysia', 'Active', 1, '2019-02-13 17:50:05', '2019-02-13 17:50:33'),
(4, 1, 'Malaysia', 'Active', 0, '2019-02-13 17:50:45', '2019-02-13 17:50:45');

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `name`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(1, 'registration', 'San App - Registration', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\r\n\r\n<div style="margin-left:200px;">\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Hi,</strong></p>\r\n\r\n<p>You are successfully registered with San App. Below is the credentials to access:</p>\r\n\r\n<p>Email: {{email}}</p>\r\n\r\n<p>Password: {{password}}</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p><strong>San Travel App</strong></p>\r\n</div>\r\n\r\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2018-02-14 21:39:22', '2018-12-26 19:37:23'),
(2, 'forgot-password', 'Forgot Password', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\r\n\r\n<div style="margin-left:200px;">\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Hello {{toUserName}},</strong></p>\r\n\r\n<p>You recently requested to reset your password for San Travel App account. Use the button below to generate it.</p>\r\n\r\n<p style="margin-left:200px"><a href="{{resetLink}}" style="background-color: #1ab394;border: solid #1ab394;border-radius: 5px;cursor: pointer;border-width: 5px 10px;color: #FFF;line-height: 2;text-align: center;text-decoration: none;text-transform: capitalize;font-weight: bold;border: solid #1ab394;padding: 10px 10px 10px 10px;">Reset Password</a></p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p><strong>San Travel App</strong></p>\r\n</div>\r\n\r\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2018-02-14 21:39:22', '2018-12-26 20:05:54'),
(3, 'merchant-register', 'Registration-SAN Travel Agent Partner Program - Account Registration', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\r\n\r\n<div style="margin-left:200px;">\r\n<p> </p>\r\n\r\n<p><strong>Dear {{toMerchantName}},</strong></p>\r\n\r\n<p>Thank you for signing up at SAN Travel Agent Program!</p>\r\n<p>What\'s next?  </p>\r\n<p>To proceed the agent account registration process, kindly advise us with the followings:</p>\r\n<ol>\r\n<li>Let us know what kind of products you are interested in?</li>\r\n<li> Sharing more details with us as below:</li>\r\n</ol>\r\n<ul>\r\n<li>Operation mode (online or offline):</li>\r\n<li>Business channel (B2B or B2C):</li>\r\n<li>Estimated monthly sales volume:</li>\r\n</ul>\r\n\r\n\r\n<p>Look forward to your reply and start accessing our competitive rates!</p>\r\n\r\n<p>Happy selling!</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p><strong>San Travel App</strong></p>\r\n</div>\r\n\r\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2019-01-16 12:04:00', '2019-01-16 12:18:46'),
(4, 'approve-activity', 'Registration-SAN Travel Agent Partner Program - Approve Activity', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\r\n\r\n<div style="margin-left:200px;">\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Dear {{toMerchantName}},</strong></p>\r\n\r\n<p>Thank you for create activity at SAN Travel Agent Program!</p>\r\n\r\n<p>Your activity: <strong>{{activityName}}</strong> has been&nbsp;approved from SAN Travel Team.</p>\r\n\r\n<p>Happy selling!</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p><strong>San Travel App</strong></p>\r\n</div>\r\n\r\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2019-01-16 12:04:00', '2019-02-08 12:03:18'),
(5, 'decline-activity', 'Registration-SAN Travel Agent Partner Program - Decline Activity', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\r\n\r\n<div style="margin-left:200px;">\r\n<p>&nbsp;</p>\r\n\r\n<p><strong>Dear {{toMerchantName}},</strong></p>\r\n\r\n<p>Thank you for create activity at SAN Travel Agent Program!</p>\r\n\r\n<p>Your activity: <strong>{{activityName}}</strong> has been&nbsp;Decline from SAN Travel Team.</p>\r\n\r\n<p>Reason for you&#39;r activity decline is :&nbsp;</p>\r\n\r\n<p>{{resonDecline}}</p>\r\n\r\n<p>Happy selling!</p>\r\n\r\n<p>Thanks</p>\r\n\r\n<p><strong>San Travel App</strong></p>\r\n</div>\r\n\r\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2019-01-16 12:04:00', '2019-01-24 13:05:19'),
(6, 'booking-note', 'Order#:', '<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 10px; color: rgb(255, 255, 255); font-size: 30px; text-align: center;">San Travel App</div>\n\n<div style="margin-left:200px;">\n<p>&nbsp;</p>\n\n<p><strong>Dear {{customer}},</strong></p>\n\n<p>Here i am writing to know you about your booking&nbsp;</p>\n\n<p>Title : {{title}}</p>\n\n<p>Description : {{description}}</p>\n\n<p>Thanks</p>\n\n<p><strong>San Travel App</strong></p>\n</div>\n\n<div dir="rtl" style="background-color: rgb(26, 179, 148); vertical-align: middle; padding: 20px; color: rgb(255, 255, 255); font-size: 15px; text-align: center;">San Travel App</div>', '2019-01-16 12:04:00', '2019-02-14 11:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `explore`
--

CREATE TABLE `explore` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `explore`
--

INSERT INTO `explore` (`id`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'comming soon', NULL, '2019-01-12 01:45:14', '2019-01-12 01:45:14');

-- --------------------------------------------------------

--
-- Table structure for table `explore_images`
--

CREATE TABLE `explore_images` (
  `id` int(11) NOT NULL,
  `explore_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `explore_images`
--

INSERT INTO `explore_images` (`id`, `explore_id`, `image`, `created_at`, `updated_at`) VALUES
(3, 1, '1547812383-Malaysia.jpg', '2019-01-18 05:23:04', '2019-01-18 05:23:04'),
(10, 1, '1550037909-IMG_144869.jpg', '2019-02-13 11:35:10', '2019-02-13 11:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `general_policies`
--

CREATE TABLE `general_policies` (
  `id` int(11) NOT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `is_activity_policy` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_policies`
--

INSERT INTO `general_policies` (`id`, `activity_id`, `name`, `icon`, `is_delete`, `is_activity_policy`, `created_at`, `updated_at`) VALUES
(1, NULL, 'No Cancellation ad', '1546600759-k-purple.jpg', 1, 0, '2019-01-01 07:00:59', '2019-02-13 11:15:45'),
(2, NULL, 'Show Mobile or Printed Voucher', '1546344858-bank-512.png', 1, 0, '2019-01-01 07:01:23', '2019-02-13 11:15:52'),
(3, NULL, 'Open Date Ticket', '1546344606-ucombinator_logo.png', 0, 0, '2019-01-01 12:09:34', '2019-01-01 12:10:06'),
(4, NULL, 'Enter Directly With Voucher', '1550038134-1546523298-flag-icon.png', 0, 0, '2019-01-02 12:51:29', '2019-02-13 11:38:54'),
(5, NULL, '45 Min Duration', '1550038148-1546344858-bank-512.png', 0, 0, '2019-01-02 12:58:45', '2019-02-13 11:39:08'),
(6, NULL, 'English / Chinese / Cantonese', '1546433958-umbrella-icon.png', 1, 0, '2019-01-02 12:59:18', '2019-02-13 11:38:34'),
(7, NULL, 'asdasd', '1546587700-KTO-Website-Banner-20-1.png', 1, 0, '2019-01-04 07:41:40', '2019-02-13 11:38:31'),
(8, NULL, 'qeq', '1546595110-KTO-Website-Banner-20-1.png', 1, 0, '2019-01-04 09:45:10', '2019-02-08 16:18:00'),
(20, 7, 'Free Cancelation - 24 Hrs Notice', '1547207489-Activites.png', 0, 1, '2019-01-11 17:22:37', '2019-01-11 17:22:37'),
(21, 7, 'Hotel Pick Up', '1547207514-Activites.png', 0, 1, '2019-01-11 17:22:37', '2019-01-11 17:22:37'),
(22, 8, 'Meet Up At Location', '1547208106-Activites.png', 0, 1, '2019-01-11 17:32:46', '2019-01-11 17:32:46'),
(24, 16, 'policy', '1549538275-preview-587f9a79-49b4-4248-9c1c-5e0b0a14153a-YY6iJ.png', 0, 1, '2019-02-07 16:57:53', '2019-02-07 16:57:53'),
(25, NULL, 'Show Mobile or Printed VoucherShow Mobile or Prin', '1549622820-60_0.png', 1, 0, '2019-02-08 16:17:00', '2019-02-13 11:38:28'),
(26, NULL, 'No Cancelation', '1550143232-1546602542-flag-icon.png', 0, 0, '2019-02-14 16:50:32', '2019-02-14 16:50:32'),
(27, NULL, 'Collect Physical Ticket', '1550143269-1546601365-flag-icon.png', 0, 0, '2019-02-14 16:51:09', '2019-02-14 16:51:09'),
(28, NULL, 'Show Mobile or Printed Voucher', '1550143299-1550037547-1546602542-flag-icon.png', 0, 0, '2019-02-14 16:51:39', '2019-02-14 16:51:39');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(1, 'system_users'),
(2, 'merchant'),
(3, 'customers'),
(4, 'activities'),
(5, 'locations'),
(6, 'categories'),
(7, 'settings'),
(8, 'dashboard');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `updated_at`) VALUES
(4, 38, 50, 'Your Lotte World 1 Day Pass Has been booked for 2019-03-28.', '2019-02-04 11:09:56', '2019-02-04 10:19:56'),
(5, 49, 1, 'Your Northern Lights Minibus Chase Tour from Tromsø Has been booked for 2019-02-19.', '2019-02-04 17:51:40', '2019-02-04 17:51:40'),
(6, 49, 1, 'Your Northern Lights Minibus Chase Tour from Tromsø Has been booked for 2019-02-19.', '2019-02-04 17:52:14', '2019-02-04 17:52:14'),
(7, 49, 59, 'Your Werribee Open Range Zoo Off Road Safari Has been booked for 2019-02-09.', '2019-02-04 18:19:28', '2019-02-04 18:19:28'),
(8, 49, 59, 'Your Werribee Open Range Zoo Off Road Safari Has been booked for 2019-02-08.', '2019-02-05 14:32:48', '2019-02-05 14:32:48'),
(9, 49, 1, 'Your Northern Lights Minibus Chase Tour from Tromsø Has been booked for 2019-02-14.', '2019-02-05 14:32:48', '2019-02-05 14:32:48'),
(10, 49, 1, 'Your Northern Lights Minibus Chase Tour from Tromsø Has been booked for 2019-03-02.', '2019-02-05 14:32:48', '2019-02-05 14:32:48'),
(11, 49, 59, 'Your Northern Lights Minibus Chase Apurv Has been booked for 2019-02-15.', '2019-02-06 12:51:28', '2019-02-06 12:51:28'),
(12, 49, 1, 'Your Northern Lights Minibus Chase Tour from Tromsø Has been booked for 2019-02-22.', '2019-02-06 16:35:52', '2019-02-06 16:35:52'),
(13, 49, 1, 'Your West Nusa Penida Whole Day Trip in Bali Has been booked for 2019-02-14.', '2019-02-06 16:52:10', '2019-02-06 16:52:10'),
(14, 68, 1, 'Your TEST RIDE Has been booked for 2019-02-14.', '2019-02-07 16:20:30', '2019-02-07 16:20:30'),
(15, 70, 1, 'Your TEST RIDE Has been booked for 2019-02-13.', '2019-02-08 19:18:23', '2019-02-08 19:18:23'),
(16, 70, 47, 'Your Gondola Rides at The Venetian Macau Has been booked for 2019-02-15.', '2019-02-09 11:54:46', '2019-02-09 11:54:46'),
(17, 68, 61, 'Your fghh Has been booked for 2019-02-14.', '2019-02-09 13:48:16', '2019-02-09 13:48:16'),
(18, 49, 59, 'Your Northern Lights Minibus Chase Apurv Has been booked for 2019-02-19.', '2019-02-11 11:24:44', '2019-02-11 11:24:44'),
(19, 49, 59, 'Your Northern Lights Minibus Chase Apurv Has been booked for 2019-02-14.', '2019-02-11 11:24:44', '2019-02-11 11:24:44'),
(20, 49, 59, 'Your Northern Lights Minibus Chase Apurv Has been booked for 2019-02-15.', '2019-02-11 11:29:24', '2019-02-11 11:29:24'),
(21, 49, 59, 'Your Northern Lights Minibus Chase Apurv Has been booked for 2019-02-14.', '2019-02-12 12:09:16', '2019-02-12 12:09:16'),
(22, 49, 1, 'Your West Nusa Penida Whole Day Trip in Bali Has been booked for 2019-2-19.', '2019-02-12 13:27:44', '2019-02-12 13:27:44'),
(23, 49, 1, 'Your Jetpack/Jetlev Flyer Experience in Goa Has been booked for 2019-02-14.', '2019-02-13 12:32:56', '2019-02-13 12:32:56'),
(24, 49, 1, 'Your Jetpack/Jetlev Flyer Experience in Goa Has been booked for 2019-02-14.', '2019-02-13 12:32:56', '2019-02-13 12:32:56'),
(25, 49, 1, 'Your Jetpack/Jetlev Flyer Experience in Goa Has been booked for 2019-2-14.', '2019-02-13 17:07:49', '2019-02-13 17:07:49'),
(26, 49, 1, 'Your Jetpack/Jetlev Flyer Experience in Goa Has been booked for 2019-02-20.', '2019-02-13 17:12:18', '2019-02-13 17:12:18'),
(27, 49, 1, 'Your Morning Tour To Bondla Wildlife Sanctuary Has been booked for 2019-02-28.', '2019-02-13 17:12:18', '2019-02-13 17:12:18'),
(28, 75, 1, 'Your Jetpack/Jetlev Flyer Experience in Goa Has been booked for 2019-2-15.', '2019-02-13 17:48:12', '2019-02-13 17:48:12'),
(29, 75, 74, 'Your Maxwell Hill in Taiping Has been booked for 2019-2-15.', '2019-02-14 11:10:21', '2019-02-14 11:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `package_quantity_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `package_price` decimal(14,2) NOT NULL,
  `total` decimal(14,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`id`, `order_id`, `package_id`, `package_quantity_id`, `quantity`, `package_price`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 1, '3000.00', '3000.00', '2019-02-13 17:48:12', '2019-02-13 17:48:12'),
(2, 2, 4, 9, 1, '18000.00', '18000.00', '2019-02-14 11:10:21', '2019-02-14 11:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `booking_date` date DEFAULT NULL,
  `order_total` decimal(14,2) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0 = Pending , 1 = Canceled, 2 = Confirmed,3 = Expired ',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `transaction_id`, `activity_id`, `booking_date`, `order_total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SAN130294571', 75, 1, 1, '2019-02-15', '3000.00', 0, '2019-02-13 17:48:12', '2019-02-13 17:48:12'),
(2, 'SAN140272662', 75, 2, 3, '2019-02-15', '18000.00', 0, '2019-02-14 11:10:21', '2019-02-14 11:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `order_log`
--

CREATE TABLE `order_log` (
  `id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `log` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_log`
--

CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL,
  `order_number` int(11) NOT NULL,
  `receipt` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `payment_type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `p_read` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Yes, 0 = No',
  `p_write` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = Yes, 0 = No',
  `p_sidebar` int(1) NOT NULL DEFAULT '1' COMMENT '1 = Yes, 0 = No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module_id`, `role_id`, `p_read`, `p_write`, `p_sidebar`) VALUES
(1, 1, 1, 1, 1, 1),
(2, 1, 2, 0, 0, 0),
(3, 1, 3, 0, 0, 0),
(4, 2, 1, 1, 1, 1),
(5, 2, 2, 0, 0, 0),
(6, 2, 3, 1, 1, 1),
(7, 3, 1, 1, 1, 1),
(8, 3, 2, 0, 0, 0),
(9, 3, 3, 1, 1, 1),
(10, 4, 1, 1, 1, 1),
(11, 4, 2, 1, 0, 1),
(12, 4, 3, 0, 0, 0),
(13, 5, 1, 1, 1, 1),
(14, 5, 2, 1, 0, 1),
(15, 5, 3, 1, 1, 1),
(16, 6, 1, 1, 1, 1),
(17, 6, 2, 1, 0, 1),
(18, 6, 3, 0, 0, 0),
(19, 7, 1, 1, 1, 1),
(20, 7, 2, 0, 0, 0),
(21, 7, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `profile_country`
--

CREATE TABLE `profile_country` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `iso3` char(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profile_country`
--

INSERT INTO `profile_country` (`id`, `iso`, `name`, `nicename`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 'AFG', 4, 93),
(2, 'AL', 'ALBANIA', 'Albania', 'ALB', 8, 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 'DZA', 12, 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 'ASM', 16, 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 'AND', 20, 376),
(6, 'AO', 'ANGOLA', 'Angola', 'AGO', 24, 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 'AIA', 660, 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', NULL, NULL, 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 'ATG', 28, 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 'ARG', 32, 54),
(11, 'AM', 'ARMENIA', 'Armenia', 'ARM', 51, 374),
(12, 'AW', 'ARUBA', 'Aruba', 'ABW', 533, 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 'AUS', 36, 61),
(14, 'AT', 'AUSTRIA', 'Austria', 'AUT', 40, 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 'AZE', 31, 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 'BHS', 44, 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 'BHR', 48, 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 'BGD', 50, 880),
(19, 'BB', 'BARBADOS', 'Barbados', 'BRB', 52, 1246),
(20, 'BY', 'BELARUS', 'Belarus', 'BLR', 112, 375),
(21, 'BE', 'BELGIUM', 'Belgium', 'BEL', 56, 32),
(22, 'BZ', 'BELIZE', 'Belize', 'BLZ', 84, 501),
(23, 'BJ', 'BENIN', 'Benin', 'BEN', 204, 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 'BMU', 60, 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 'BTN', 64, 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 'BOL', 68, 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 'BIH', 70, 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 'BWA', 72, 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', NULL, NULL, 0),
(30, 'BR', 'BRAZIL', 'Brazil', 'BRA', 76, 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', NULL, NULL, 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 'BRN', 96, 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 'BGR', 100, 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 'BFA', 854, 226),
(35, 'BI', 'BURUNDI', 'Burundi', 'BDI', 108, 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 'KHM', 116, 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 'CMR', 120, 237),
(38, 'CA', 'CANADA', 'Canada', 'CAN', 124, 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 'CPV', 132, 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 'CYM', 136, 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 'CAF', 140, 236),
(42, 'TD', 'CHAD', 'Chad', 'TCD', 148, 235),
(43, 'CL', 'CHILE', 'Chile', 'CHL', 152, 56),
(44, 'CN', 'CHINA', 'China', 'CHN', 156, 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', NULL, NULL, 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', NULL, NULL, 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 'COL', 170, 57),
(48, 'KM', 'COMOROS', 'Comoros', 'COM', 174, 269),
(49, 'CG', 'CONGO', 'Congo', 'COG', 178, 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 'COD', 180, 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 'COK', 184, 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 'CRI', 188, 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 'CIV', 384, 225),
(54, 'HR', 'CROATIA', 'Croatia', 'HRV', 191, 385),
(55, 'CU', 'CUBA', 'Cuba', 'CUB', 192, 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 'CYP', 196, 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 'CZE', 203, 420),
(58, 'DK', 'DENMARK', 'Denmark', 'DNK', 208, 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 'DJI', 262, 253),
(60, 'DM', 'DOMINICA', 'Dominica', 'DMA', 212, 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 'DOM', 214, 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 'ECU', 218, 593),
(63, 'EG', 'EGYPT', 'Egypt', 'EGY', 818, 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 'SLV', 222, 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 'GNQ', 226, 240),
(66, 'ER', 'ERITREA', 'Eritrea', 'ERI', 232, 291),
(67, 'EE', 'ESTONIA', 'Estonia', 'EST', 233, 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 'ETH', 231, 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 'FLK', 238, 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 'FRO', 234, 298),
(71, 'FJ', 'FIJI', 'Fiji', 'FJI', 242, 679),
(72, 'FI', 'FINLAND', 'Finland', 'FIN', 246, 358),
(73, 'FR', 'FRANCE', 'France', 'FRA', 250, 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 'GUF', 254, 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 'PYF', 258, 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', NULL, NULL, 0),
(77, 'GA', 'GABON', 'Gabon', 'GAB', 266, 241),
(78, 'GM', 'GAMBIA', 'Gambia', 'GMB', 270, 220),
(79, 'GE', 'GEORGIA', 'Georgia', 'GEO', 268, 995),
(80, 'DE', 'GERMANY', 'Germany', 'DEU', 276, 49),
(81, 'GH', 'GHANA', 'Ghana', 'GHA', 288, 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 'GIB', 292, 350),
(83, 'GR', 'GREECE', 'Greece', 'GRC', 300, 30),
(84, 'GL', 'GREENLAND', 'Greenland', 'GRL', 304, 299),
(85, 'GD', 'GRENADA', 'Grenada', 'GRD', 308, 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 'GLP', 312, 590),
(87, 'GU', 'GUAM', 'Guam', 'GUM', 316, 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 'GTM', 320, 502),
(89, 'GN', 'GUINEA', 'Guinea', 'GIN', 324, 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 'GNB', 624, 245),
(91, 'GY', 'GUYANA', 'Guyana', 'GUY', 328, 592),
(92, 'HT', 'HAITI', 'Haiti', 'HTI', 332, 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', NULL, NULL, 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 'VAT', 336, 39),
(95, 'HN', 'HONDURAS', 'Honduras', 'HND', 340, 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 'HKG', 344, 852),
(97, 'HU', 'HUNGARY', 'Hungary', 'HUN', 348, 36),
(98, 'IS', 'ICELAND', 'Iceland', 'ISL', 352, 354),
(99, 'IN', 'INDIA', 'India', 'IND', 356, 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 'IDN', 360, 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 'IRN', 364, 98),
(102, 'IQ', 'IRAQ', 'Iraq', 'IRQ', 368, 964),
(103, 'IE', 'IRELAND', 'Ireland', 'IRL', 372, 353),
(104, 'IL', 'ISRAEL', 'Israel', 'ISR', 376, 972),
(105, 'IT', 'ITALY', 'Italy', 'ITA', 380, 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 'JAM', 388, 1876),
(107, 'JP', 'JAPAN', 'Japan', 'JPN', 392, 81),
(108, 'JO', 'JORDAN', 'Jordan', 'JOR', 400, 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 'KAZ', 398, 7),
(110, 'KE', 'KENYA', 'Kenya', 'KEN', 404, 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 'KIR', 296, 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 'PRK', 408, 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 'KOR', 410, 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 'KWT', 414, 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 'KGZ', 417, 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 'LAO', 418, 856),
(117, 'LV', 'LATVIA', 'Latvia', 'LVA', 428, 371),
(118, 'LB', 'LEBANON', 'Lebanon', 'LBN', 422, 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 'LSO', 426, 266),
(120, 'LR', 'LIBERIA', 'Liberia', 'LBR', 430, 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 'LBY', 434, 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 'LIE', 438, 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 'LTU', 440, 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 'LUX', 442, 352),
(125, 'MO', 'MACAO', 'Macao', 'MAC', 446, 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 'MKD', 807, 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 'MDG', 450, 261),
(128, 'MW', 'MALAWI', 'Malawi', 'MWI', 454, 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 'MYS', 458, 60),
(130, 'MV', 'MALDIVES', 'Maldives', 'MDV', 462, 960),
(131, 'ML', 'MALI', 'Mali', 'MLI', 466, 223),
(132, 'MT', 'MALTA', 'Malta', 'MLT', 470, 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 'MHL', 584, 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 'MTQ', 474, 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 'MRT', 478, 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 'MUS', 480, 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', NULL, NULL, 269),
(138, 'MX', 'MEXICO', 'Mexico', 'MEX', 484, 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 'FSM', 583, 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 'MDA', 498, 373),
(141, 'MC', 'MONACO', 'Monaco', 'MCO', 492, 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 'MNG', 496, 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 'MSR', 500, 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 'MAR', 504, 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 'MOZ', 508, 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 'MMR', 104, 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 'NAM', 516, 264),
(148, 'NR', 'NAURU', 'Nauru', 'NRU', 520, 674),
(149, 'NP', 'NEPAL', 'Nepal', 'NPL', 524, 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 'NLD', 528, 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 'ANT', 530, 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 'NCL', 540, 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 'NZL', 554, 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 'NIC', 558, 505),
(155, 'NE', 'NIGER', 'Niger', 'NER', 562, 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 'NGA', 566, 234),
(157, 'NU', 'NIUE', 'Niue', 'NIU', 570, 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 'NFK', 574, 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 'MNP', 580, 1670),
(160, 'NO', 'NORWAY', 'Norway', 'NOR', 578, 47),
(161, 'OM', 'OMAN', 'Oman', 'OMN', 512, 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 'PAK', 586, 92),
(163, 'PW', 'PALAU', 'Palau', 'PLW', 585, 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', NULL, NULL, 970),
(165, 'PA', 'PANAMA', 'Panama', 'PAN', 591, 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 'PNG', 598, 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 'PRY', 600, 595),
(168, 'PE', 'PERU', 'Peru', 'PER', 604, 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 'PHL', 608, 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 'PCN', 612, 0),
(171, 'PL', 'POLAND', 'Poland', 'POL', 616, 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 'PRT', 620, 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 'PRI', 630, 1787),
(174, 'QA', 'QATAR', 'Qatar', 'QAT', 634, 974),
(175, 'RE', 'REUNION', 'Reunion', 'REU', 638, 262),
(176, 'RO', 'ROMANIA', 'Romania', 'ROM', 642, 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643, 70),
(178, 'RW', 'RWANDA', 'Rwanda', 'RWA', 646, 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 'SHN', 654, 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 'KNA', 659, 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 'LCA', 662, 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 'SPM', 666, 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 'VCT', 670, 1784),
(184, 'WS', 'SAMOA', 'Samoa', 'WSM', 882, 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 'SMR', 674, 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 'STP', 678, 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 'SAU', 682, 966),
(188, 'SN', 'SENEGAL', 'Senegal', 'SEN', 686, 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', NULL, NULL, 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 'SYC', 690, 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 'SLE', 694, 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 'SGP', 702, 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 'SVK', 703, 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 'SVN', 705, 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 'SLB', 90, 677),
(196, 'SO', 'SOMALIA', 'Somalia', 'SOM', 706, 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 'ZAF', 710, 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', NULL, NULL, 0),
(199, 'ES', 'SPAIN', 'Spain', 'ESP', 724, 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 'LKA', 144, 94),
(201, 'SD', 'SUDAN', 'Sudan', 'SDN', 736, 249),
(202, 'SR', 'SURINAME', 'Suriname', 'SUR', 740, 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 'SJM', 744, 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 'SWZ', 748, 268),
(205, 'SE', 'SWEDEN', 'Sweden', 'SWE', 752, 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 'CHE', 756, 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 'SYR', 760, 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 'TWN', 158, 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 'TJK', 762, 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 'TZA', 834, 255),
(211, 'TH', 'THAILAND', 'Thailand', 'THA', 764, 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', NULL, NULL, 670),
(213, 'TG', 'TOGO', 'Togo', 'TGO', 768, 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 'TKL', 772, 690),
(215, 'TO', 'TONGA', 'Tonga', 'TON', 776, 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 'TTO', 780, 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 'TUN', 788, 216),
(218, 'TR', 'TURKEY', 'Turkey', 'TUR', 792, 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 'TKM', 795, 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 'TCA', 796, 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 'TUV', 798, 688),
(222, 'UG', 'UGANDA', 'Uganda', 'UGA', 800, 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 'UKR', 804, 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 'ARE', 784, 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 'GBR', 826, 44),
(226, 'US', 'UNITED STATES', 'United States', 'USA', 840, 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', NULL, NULL, 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 'URY', 858, 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 'UZB', 860, 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 'VUT', 548, 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 'VEN', 862, 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 'VNM', 704, 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 'VGB', 92, 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 'VIR', 850, 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 'WLF', 876, 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 'ESH', 732, 212),
(237, 'YE', 'YEMEN', 'Yemen', 'YEM', 887, 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 'ZMB', 894, 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 'ZWE', 716, 263);

-- --------------------------------------------------------

--
-- Table structure for table `reviews_images`
--

CREATE TABLE `reviews_images` (
  `id` int(11) NOT NULL,
  `activity_reviews_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2018-02-14 21:39:22', '2018-02-14 21:39:22'),
(2, 'Customer', '2018-02-14 21:39:22', '2018-02-14 21:39:22'),
(3, 'Merchant', '2018-02-14 21:39:22', '2018-01-29 23:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `state` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subcategory`
--

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subcategory`
--

INSERT INTO `subcategory` (`id`, `category_id`, `name`, `status`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nature Escapes', 'Active', 0, '2019-02-13 11:51:37', '2019-02-13 11:51:37'),
(2, 1, 'Theme Parks', 'Active', 0, '2019-02-13 11:51:37', '2019-02-13 11:51:37'),
(3, 2, 'Island Hopping', 'Active', 0, '2019-02-13 11:52:10', '2019-02-13 11:52:10'),
(4, 2, 'Beyond the City', 'Active', 0, '2019-02-13 11:52:10', '2019-02-13 11:52:10'),
(5, 4, 'Skiing & Snow Sports', 'Active', 0, '2019-02-14 16:57:28', '2019-02-14 16:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_number`, `created_at`, `updated_at`) VALUES
(1, 'TXN1550060292SAN26', '2019-02-13 17:48:12', '2019-02-13 17:48:12'),
(2, 'TXN1550122821SAN31', '2019-02-14 11:10:21', '2019-02-14 11:10:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` text COLLATE utf8mb4_unicode_ci,
  `family_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `voucher_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sst_certificate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_token` longtext COLLATE utf8mb4_unicode_ci,
  `device_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_token` longtext COLLATE utf8mb4_unicode_ci,
  `reset_token` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registration_type` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Normal, 2 = Facebook',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `title`, `profile_photo`, `family_name`, `company_name`, `country_name`, `country_code`, `city_name`, `email`, `voucher_email`, `mobile_number`, `website`, `sst_certificate`, `password`, `remember_token`, `user_token`, `device_type`, `device_token`, `reset_token`, `status`, `facebook_id`, `registration_type`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 1, 'aipxperts', NULL, NULL, 'AIPXperts', NULL, NULL, NULL, NULL, 'info@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$4O1giUbXcJavnk/aPaRVT.rY59zbpwvhHsGX4afCw.V1fASBFsk9.', 'c22sQkSPhz22VxwBzLWpf1KxI7ftiaHSmtHwzxtS39JYxHmLnyWecsVwR9Dn', NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, NULL, NULL),
(37, 2, 'Ritesh Shah', NULL, 'https://graph.facebook.com/v3.2/355878035229795/picture?height=200&width=200&migration_overrides=%7Boctober_2012%3Atrue%7D', NULL, NULL, NULL, NULL, NULL, 'test01.aipxperts@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjM3LCJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjM1L3Nhbi1waHAvYXBpL3YxL2ZhY2Vib29rbG9naW4iLCJpYXQiOjE1NDYwMDUxMzQsImV4cCI6MTU0NjAwODczNCwibmJmIjoxNTQ2MDA1MTM0LCJqdGkiOiJlSUFCS3NYS2hlWG1vQUt1In0.LjfLm_LydOGnTfcg8mGxk6H_ICMoiLgJhdkmX7lrzP8', 'android', 'test', NULL, 'Active', '355878035229795', 2, 0, '2018-12-28 13:52:14', '2018-12-28 13:52:14'),
(38, 2, 'ABC', NULL, 'https://static.developer.intuit.com/images/Invoice1040.jpg', NULL, NULL, NULL, NULL, NULL, 'abc@aipxperts.com', NULL, '9714497138', NULL, NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjM4LCJpc3MiOiJodHRwOi8vMTkyLjE2OC4xLjM1L3Nhbi1waHAvYXBpL3YxL2ZhY2Vib29rbG9naW4iLCJpYXQiOjE1NDYwNzY2MTAsImV4cCI6MTU0NjA4MDIxMCwibmJmIjoxNTQ2MDc2NjEwLCJqdGkiOiJkTzZIVDhUZVZUZVRKWXRsIn0.UpnqfKh6ysUfS6VE005n2jah-7wf_7t01xO2dnmohwQ', 'android', '34kk4g3k4g3k4g34k343g43kg4343434', NULL, 'Inactive', '2323232332323', 2, 1, '2018-12-29 09:43:30', '2018-12-29 09:43:30'),
(42, 2, 'Marmik', 'Mr', '1546838619-LIW_icon.png', 'Marmik patel', NULL, 'India', '+91', NULL, 'ramesh.chudasama@aipxperts.com', NULL, '123465789', NULL, NULL, '$2y$10$2ZNBkNJGlYT21bN9EFr3r..bzp1p1q5btPNHZeiy4eI9PsM6eHjpC', NULL, '', '', '', NULL, 'Active', NULL, 1, 0, '2019-01-05 07:38:38', '2019-01-07 05:48:48'),
(40, 1, 'Mamrik', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'marmik.patel@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$Hmiq1KpGXw4Yi0oo8HBPPO.ZDso2FvZAqk10zd.BizXfUzjEMuiP6', 'bCorNCMLaWByewNSldNi4q6WKciT3qCFxnNsEKiwT06XGrqfBcwd9rnKuqnc', NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-01-03 06:12:23', '2019-01-03 06:12:23'),
(41, 2, 'kevin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'kevin.dudani@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$3HALbIbLs5BjdwONYXkXZu6hQoZpd764gP/upb69c07ZjK9Hx4EM.', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQxLCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NTAwNDkyMDYsImV4cCI6MTU1MDA1MjgwNiwibmJmIjoxNTUwMDQ5MjA2LCJqdGkiOiJLZ2paRGpiN0QycW1MVDg4In0.XvG9JkkdLd3gIAaJcTK7xzoydNiJDxMSEDat0A5ouCk', 'android', '34kk4g3k4g3k4g34k343g43kg4343434', NULL, 'Active', NULL, 1, 0, '2019-01-03 07:21:24', '2019-01-10 11:14:57'),
(47, 3, 'Kaushik Maru', NULL, NULL, NULL, 'KaushikXperts', 'Malaysia', NULL, 'KP', 'kaushik.maru@aipxperts.com', NULL, '798456132', 'http://google.com', 'Xperts456', '$2y$10$LaasQXz.63NfiiYhgKjQ0Ov6uyDIOEJddIz86QALDYgHMWz0nXFoW', 'K7jSw7ASMbbAqHOQ8SVYPEzNc90GWa8OWl086pjEh2NjFEL4gbbimWT6NkqE', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ3LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbWVyY2hhbnRsb2dpbiIsImlhdCI6MTU0ODkzMDE4MiwiZXhwIjoxNTQ4OTMzNzgyLCJuYmYiOjE1NDg5MzAxODIsImp0aSI6InppUnVpRTZLOWoyV2FpS0oifQ.ZAprs3FIjYsqX0EFbij-sBRl4HQwAuKCJDpFPlUncWE', 'android', '34kk4g3k4g3k4g34k343g43kg4343434', '2019-01-25 12:47:34', 'Active', NULL, 1, 1, '2019-01-17 11:13:27', '2019-01-25 12:47:34'),
(44, 2, 'test14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test14@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$eCNIDS5tiY2WQ3KjBlCOhuh1d1lKi3h5jItPNCpWugRvNg8z3XaWC', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ0LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NDcwOTk4MzAsImV4cCI6MTU0NzEwMzQzMCwibmJmIjoxNTQ3MDk5ODMwLCJqdGkiOiJyN0M4bnlzVzJEWUV2c21FIn0.xJZfutYydYIKkt180_Rtpqb5eMV_ELSV32TkmnH_cio', 'android', 'test', NULL, 'Active', NULL, 1, 1, '2019-01-10 11:12:11', '2019-01-10 11:24:46'),
(45, 2, 'Dominic Tan', NULL, 'https://graph.facebook.com/v3.2/10156428435293037/picture?height=200&width=200&migration_overrides=%7Boctober_2012%3Atrue%7D', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ1LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvZmFjZWJvb2tsb2dpbiIsImlhdCI6MTU0NzY5ODE3OCwiZXhwIjoxNTQ3NzAxNzc4LCJuYmYiOjE1NDc2OTgxNzgsImp0aSI6IkJDYnpuWjZ3NWczeGQzek4ifQ.Ldg-WxrAnSZ57sr0BVirjpSLzALFo30NbWxu_67C2-k', 'android', 'test', NULL, 'Active', '10156428435293037', 2, 0, '2019-01-17 09:39:38', '2019-01-17 09:39:38'),
(46, 3, 'Jay Gohil', NULL, NULL, NULL, 'JayXperts', 'india', NULL, 'Ahmedabad', 'jay.gohil@aipxperts.com', NULL, '132456', 'http://google.com', 'Xperts12345a', '$2y$10$GQ2X9O..nJo.w2yrZUwu6ebjr.P1YmmbvaFGGYwl98ALTjle5Ns8u', NULL, NULL, NULL, NULL, '2019-02-04 11:53:28', 'Active', NULL, 1, 1, '2019-01-17 11:09:46', '2019-02-08 12:35:50'),
(48, 2, 'ritesh shah', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'riteshloud@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$36Occrh.FVDR3mvXHMt76OYvPA5H9dH6MJ1c.CEDQDtHrZ/caTOvy', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ4LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvY3VzdG9tZXJfcmVnaXN0cmF0aW9uIiwiaWF0IjoxNTQ3NzA2NzQ0LCJleHAiOjE1NDc3MTAzNDQsIm5iZiI6MTU0NzcwNjc0NCwianRpIjoiUlF4bjFoOGZUeU1xbkF6MCJ9.Sm1BVojahx8ppH40ikrahsFczZFv5nveuLO-amnBRF8', 'android', 'test', NULL, 'Active', NULL, 1, 0, '2019-01-17 12:02:21', '2019-01-17 12:02:21'),
(49, 2, 'Dharmesh', 'Mr', '1549013291-1549013288328.jpg', 'Dharmesh Patel', NULL, 'Canada', '+60', NULL, 'dharmesh.dudhat@aipxperts.com', NULL, '8787686767', NULL, NULL, '$2y$10$SH.sBgK2NRcTRcYCtxVV8uts5oG.akKkxcCSdE6U7qgO3aiQQMQby', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjQ5LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NTAxMzc0MzcsImV4cCI6MTU1MDE0MTAzNywibmJmIjoxNTUwMTM3NDM3LCJqdGkiOiJSRWdjUndCclpMcElqR3QxIn0.hRWAxWLnAJFc3IBnG2zK0fhBiO-2ZN1yajsxPtzKz_4', 'android', 'test', '2019-02-04 01:18:55', 'Active', NULL, 1, 0, '2019-01-17 16:16:45', '2019-02-14 15:05:53'),
(57, 2, 'test07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'test07@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$p0dwDLH4aeeJ.UWpyd.lZeF10dNtLJX1cL/ShD5R8ZSuP26Y8jzo.', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjU3LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NDg2Njk0OTEsImV4cCI6MTU0ODY3MzA5MSwibmJmIjoxNTQ4NjY5NDkxLCJqdGkiOiJOeXFHTHVNY2l4V213NWZCIn0.y9cCtT4BGwkh49jtbCABw4nGTVTcf_QGgUTMGway5eQ', 'android', 'test', NULL, 'Active', NULL, 1, 0, '2019-01-28 10:59:35', '2019-01-28 10:59:35'),
(55, 3, 'arvind', NULL, NULL, NULL, 'arvind', 'USA', NULL, 'NY', 'arvind@aipxperts.com', NULL, '134', 'http://google.com', 'USANY1549', '$2y$10$aRaE6s34mYUt.utCIX.K3ONp/5flVi21zyr6ORTdmxVMiCKZSCzYm', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-01-18 12:29:37', '2019-01-18 12:29:37'),
(56, 2, 'hardik', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'harryrshah@gmail.com', NULL, NULL, NULL, NULL, '$2y$10$gNdxgIRl83EPk/QtsWZ/lOx9L5MJt0Xe7I8Mm4g6DqYqqODa9hXzm', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjU2LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NDg0MDk2MzUsImV4cCI6MTU0ODQxMzIzNSwibmJmIjoxNTQ4NDA5NjM1LCJqdGkiOiJ6Y25MNGxLOE9yT25SSVdzIn0.NGgTfxpQbysfxTujuWbAbdua0b8sCkZZoJ_vTM2bly4', 'android', 'test', NULL, 'Active', NULL, 1, 0, '2019-01-21 16:46:45', '2019-01-21 16:46:45'),
(58, 2, 'Apurv Modi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'apurv.modi@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$p4QQnKV01dgqVIuCouTdV.zrO5dYk/1HMD/HJAlmauqgl8IY9JveG', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjU4LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvY3VzdG9tZXJfcmVnaXN0cmF0aW9uIiwiaWF0IjoxNTQ4OTM0MDA4LCJleHAiOjE1NDg5Mzc2MDgsIm5iZiI6MTU0ODkzNDAwOCwianRpIjoicEM2VXU3SzZ3RjBkYm54RSJ9.WJOsnUlGD-Ba-dTNBicBKSQtLG9geb-W6wqLPiL69AY', 'android', 'test', '2019-02-14 12:14:16', 'Active', NULL, 1, 1, '2019-01-31 16:56:45', '2019-02-14 12:14:16'),
(59, 3, 'Apurv', NULL, NULL, NULL, 'ApurvXperts', 'India', NULL, 'Ahmedabad', 'apurv@gmail.com', NULL, '1234865', 'http://google.com', 'san5481', '$2y$10$pDqRQ8LUXSbrqVTVH3..XOXRhs5RMxvsb3Y1hbZIRva.YZJgWlM/K', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjU5LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbWVyY2hhbnRsb2dpbiIsImlhdCI6MTU0OTYxMDExOCwiZXhwIjoxNTQ5NjEzNzE4LCJuYmYiOjE1NDk2MTAxMTgsImp0aSI6IkZvVkVpZFNETlZiRnl4U0MifQ.vtFYQPTJ4tDYdgIKm7hQ7VrsLpXw-PwEbLEOUyfThLE', 'android', NULL, '2019-02-05 11:11:22', 'Active', NULL, 1, 1, '2019-01-31 17:57:47', '2019-02-06 23:13:34'),
(60, 1, 'Ankita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ankita.vyas@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$1DxpOnJdk78TggOtk4WEUuP6lxNwY8SLMosp4Qv1KrXM9PYenipz.', NULL, NULL, NULL, NULL, '2019-02-06 06:15:38', 'Active', NULL, 1, 1, '2019-02-06 17:44:25', '2019-02-06 06:15:38'),
(61, 1, 'ankita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ankita.vyas@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$PwGnq0gydz.6HTmaHnnACeh4jfn8AD6d6gdTGTvpEj67MWX/Rb7Ca', 'XaqQBE56dIwk3dJSVKr2abbysGN4hAI5lTEl4yBeXEOt6oXANDN00m1qj6j7', NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-06 17:52:48', '2019-02-06 05:53:11'),
(62, 1, 'Yogesh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'y@g.com', NULL, NULL, NULL, NULL, '$2y$10$8YNNr8A8LTBjtdujN9ttgenlcTOU4iGCLZBLJ/XFXun.2DwsRSRl2', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-06 17:53:38', '2019-02-06 17:53:38'),
(63, 1, 'vyas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'vyas@g.com', NULL, NULL, NULL, NULL, '$2y$10$oOhkNgGpo/MvESW1yLiJCe5b95JAn.2J4rxaesfD8iQjvbvab01ei', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-06 17:54:19', '2019-02-06 17:54:19'),
(64, 1, 'ankita', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ak@g.com', NULL, NULL, NULL, NULL, '$2y$10$Vb6qpduPcce5r0O1eaEHmO3v99Zpiih34RnFViHAZoDgnvxnpge9.', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-06 18:03:42', '2019-02-06 18:03:42'),
(65, 3, 'sam', NULL, NULL, NULL, 'aipxp', 'Malaysia', NULL, 'Kuala Lumpur', 'sam@g.com', NULL, '99999555', 'Google', 'gg', '$2y$10$6rj9nwNH9cxa3F7NRKQT1e1Sd03YehxvoXfdZmsAOCjIezPiELZEK', 'RG5nwgpGflYVGL5ZNJwthkIqN7FJy1q8XAURq0IxmunBxXQZMxE7dNyok54S', NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-06 19:05:21', '2019-02-07 12:36:48'),
(66, 3, 'ghffhghhhhhhhhhhhhhhhhhhhhhh', NULL, NULL, NULL, 'gggggffffffffffffffffffffffffffffhhhhhhhhhhhhhhhhhhhhhh', '655555555555', NULL, 'Kuala Lumpur', 'gh@g.com', NULL, '444444544444444444444444444444444444444444444', 'ghfgh', '%$$$$$$$', '$2y$10$1pghyQy1HEaDgv5KHPdPw.pA4iRKHJ8dSYhVAW6QYqGhRdW28jcse', NULL, NULL, NULL, NULL, NULL, 'Inactive', NULL, 1, 1, '2019-02-06 19:06:28', '2019-02-07 11:32:31'),
(70, 2, 'yogesh', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yogesh.chitte@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$8Y/v7rcYcZGu9GciaknRbug515fWJUBe.8/v5BwjC9BuZXoTfXL5u', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjcwLCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NTAxMjQ1NTgsImV4cCI6MTU1MDEyODE1OCwibmJmIjoxNTUwMTI0NTU4LCJqdGkiOiIxdGh4U1c2OXh1U2lkUmF1In0.4qghxJUNkmYTYgn8zmkgxlH4y8yW8J87Xio9l3cBo1I', 'android', '34kk4g3k4g3k4g34k343g43kg4343434', NULL, 'Active', NULL, 1, 0, '2019-02-08 13:05:34', '2019-02-08 13:05:34'),
(67, 3, 'kevin', NULL, NULL, NULL, 'aip2', 'gjhgkk', NULL, 'ghjgh', 'k@g.com', NULL, 'fhgfgfgfggh', 'fghfgfhg', '45667', '$2y$10$9YhNSaYOiPOHbX5UbcBbC.9BYacu5tcZ8PzjQHKXNcfphvlNeZiXe', NULL, NULL, NULL, NULL, NULL, 'Inactive', NULL, 1, 1, '2019-02-07 12:11:59', '2019-02-07 12:11:59'),
(68, 2, 'ankita', 'Mr', '1549545674-1549545672808.jpg', 'we', NULL, 'India', '+91', NULL, 'ankita@aipxperts.com', NULL, '97959959955', NULL, NULL, '$2y$10$0nEUfDZA1bZbBy70yaQB8OWthFxzESriYhesme6/RZmui4pOPM0k2', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjY4LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbG9naW4iLCJpYXQiOjE1NDk2MzI5OTAsImV4cCI6MTU0OTYzNjU5MCwibmJmIjoxNTQ5NjMyOTkwLCJqdGkiOiJ0NUpKaEdCaGRJOXhISTF4In0.su7MGrWPMeWdaxPYWuQ7Hq5RgeZhNfXW3V8xhTSSSi8', 'android', 'test', NULL, 'Active', NULL, 1, 1, '2019-02-07 16:13:05', '2019-02-08 12:13:57'),
(69, 2, 'Jenifer A Winget', NULL, 'https://graph.facebook.com/v3.2/141162070234071/picture?height=200&width=200&migration_overrides=%7Boctober_2012%3Atrue%7D', NULL, NULL, NULL, NULL, NULL, 'ankita.vyas@aipxperts.com', NULL, NULL, NULL, NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjY5LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvZmFjZWJvb2tsb2dpbiIsImlhdCI6MTU0OTYxMTE1NywiZXhwIjoxNTQ5NjE0NzU3LCJuYmYiOjE1NDk2MTExNTcsImp0aSI6IlpwZENNZldWa3FLalFkV1AifQ.pg9ELk6G3YzVuQP_c9jjHltgoJe_sof8NQP0V51uH4Y', 'android', 'test', NULL, 'Active', '141162070234071', 2, 0, '2019-02-08 12:43:25', '2019-02-08 12:43:25'),
(71, 3, 'Ankita', NULL, NULL, NULL, 'Aipxperts', 'India', NULL, 'Ahmedabad', 'juhi.gandhi@aipxperts.com', NULL, '9897798889', 'https://www.google.com', 'SRADFF', '$2y$10$qadAt3Q.b42fc0MOmRveMO8NtwPDNocdrwu9s1O1EpEosxaRjcmT.', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-12 18:20:36', '2019-02-12 18:20:36'),
(72, 3, 'Apurv', NULL, NULL, NULL, 'Aipxperts', 'India', NULL, 'Kuala Lumpur', 'apurv.modi@aipxperts.com', NULL, '9897798889', 'https://www.google.com', 'SAFFGG', '$2y$10$zwnV0IQaTskg4CZyaSfAJOPALGoaixW/IOITmLefv1WuW4h3swhkW', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-12 18:23:24', '2019-02-12 18:23:24'),
(73, 3, 'Jerome Tan', NULL, NULL, NULL, 'San sdn bhd', 'Malaysia', NULL, 'KL', 'jerome@sanapp.com', NULL, '4545454545', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$817DFALs6Khuo61GiIxG1.ieqN9sZLhYsOSEtXvSBJ7awKX7afL.K', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-12 18:29:28', '2019-02-12 18:29:28'),
(74, 3, 'Apurv modi', NULL, NULL, NULL, 'Aip', 'India', NULL, 'Ahmedabad', 'apurv.modi@aipxperts.com', NULL, '9999888844', 'https://www.google.co.in', 'xyz', '$2y$10$gW8dPa7HYq4aBy0awgajQel2NgtklMomIw70XJsVLN8Lycke3wY1K', 'rRA8BdeXDiD1yA2aXEJ58TW2S8LsYTRIlXjWjRquGYWh1ro2iIWQIdCUa4GJ', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjc0LCJpc3MiOiJodHRwOi8vMTU5Ljg5LjIwMS4xMi9hcGkvdjEvbWVyY2hhbnRsb2dpbiIsImlhdCI6MTU1MDEzNTcyMiwiZXhwIjoxNTUwMTM5MzIyLCJuYmYiOjE1NTAxMzU3MjIsImp0aSI6InJ2WTRibjZxTmhyZmNQTXMifQ.DmNWNSTPV1SHqQfK7C3CT9FF2c-F1gW0UeaGbN0Gkq8', 'android', NULL, NULL, 'Active', NULL, 1, 0, '2019-02-13 15:12:34', '2019-02-13 15:12:34'),
(77, 3, 'josef', NULL, NULL, NULL, 'j&D', 'Malaysia', NULL, 'KL', 'josef@gmail.com', NULL, '123123123', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$ecHYSmfnTz3IN4oZJGin0eGOI07jKeQNOAETOUKRH0St.QJKSfKe2', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 16:29:56', '2019-02-14 16:29:56'),
(75, 2, 'maulik', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'maulik.raiyani@aipxperts.com', NULL, NULL, NULL, NULL, '$2y$10$vmKHZ871/iqJ2nnkYKVll.YaM948pzhtWPLj4leTMdJQMDm3q6dEa', NULL, '', '', '', NULL, 'Active', NULL, 1, 0, '2019-02-13 17:47:22', '2019-02-14 11:10:27'),
(76, 3, 'john', NULL, NULL, NULL, 'J&D', 'Malaysia', NULL, 'KL', 'jdear@gmail.com', NULL, '123123123', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$AseWkNbw9Axg8uTPJ3h5VO8LRXi7gCrpM0cN14DcBfkqbZLPXvqqm', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 16:18:47', '2019-02-14 16:18:47'),
(78, 3, 'malik', NULL, NULL, NULL, 'Reddy.co', 'Malaysia', NULL, 'KL', 'malik@gmail.com', NULL, '1231231233', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$vCk/IZzl/CcyPUp2OfTlK..85VwmgwR2TGmqltcv1l2SZ7rlXfsBi', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 16:43:15', '2019-02-14 16:43:15'),
(79, 3, 'johnd', NULL, NULL, NULL, 'jd', 'Malaysia', NULL, 'KL', 'jdear111@gmail.com', NULL, '1231231233', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$NvHLbi7BQFBQ1PyCOkLxzus1OokYm/WkJYfAtpOE16VI2Q82tYbnu', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 17:05:32', '2019-02-14 17:05:32'),
(80, 3, 'malik', NULL, NULL, NULL, 'ank@co', 'Malaysia', NULL, 'KL', 'malikD@gmail.com', NULL, '1231231233', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$nCgg0A9eJgstSa8akepXu.IXjrO5/JxsUvhOyjgYN0ha3GBV2ZoxS', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 17:08:03', '2019-02-14 17:08:03'),
(81, 3, 'jon', NULL, NULL, NULL, 'ska.co', 'Malaysia', NULL, 'KL', 'jon@gmail.com', NULL, '1231231233', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$Dmq.W23FpUK63489WIgrV.IeYDRUoSZxItRzSa4IT1uEFIPRaU56i', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 1, '2019-02-14 17:12:09', '2019-02-14 17:12:09'),
(82, 3, 'John', NULL, NULL, NULL, 'john and sons.', 'Malaysia', NULL, 'KL', 'john@gmail.com', NULL, '123123123', 'https://www.sanapp.com', 'dummy certificate', '$2y$10$ySuu4VZEYGuCX/njltso/eO8UVyhamGWs6IkQqVUsWHGk9KHBNnVC', NULL, NULL, NULL, NULL, NULL, 'Active', NULL, 1, 0, '2019-02-14 17:18:04', '2019-02-14 17:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `activity_id`, `created_at`, `updated_at`) VALUES
(1, 75, 3, '2019-02-13 18:56:30', '2019-02-13 18:56:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `activity_faqs`
--
ALTER TABLE `activity_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `activity_package_options`
--
ALTER TABLE `activity_package_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `activity_package_quantity`
--
ALTER TABLE `activity_package_quantity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_package_id` (`activity_package_id`);

--
-- Indexes for table `activity_policies`
--
ALTER TABLE `activity_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Indexes for table `activity_reviews`
--
ALTER TABLE `activity_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_quantity_id` (`package_quantity_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`country_id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `city_categories`
--
ALTER TABLE `city_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `continents`
--
ALTER TABLE `continents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `continent_id` (`continent_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `explore`
--
ALTER TABLE `explore`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `explore_images`
--
ALTER TABLE `explore_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `explore_id` (`explore_id`);

--
-- Indexes for table `general_policies`
--
ALTER TABLE `general_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `package_quantity_id` (`package_quantity_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `activity_id` (`activity_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_log`
--
ALTER TABLE `order_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_number`);

--
-- Indexes for table `payment_log`
--
ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_number` (`order_number`);

--
-- Indexes for table `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_type` (`payment_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `profile_country`
--
ALTER TABLE `profile_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews_images`
--
ALTER TABLE `reviews_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_reviews_id` (`activity_reviews_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `subcategory`
--
ALTER TABLE `subcategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `activity_faqs`
--
ALTER TABLE `activity_faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `activity_package_options`
--
ALTER TABLE `activity_package_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `activity_package_quantity`
--
ALTER TABLE `activity_package_quantity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `activity_policies`
--
ALTER TABLE `activity_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `activity_reviews`
--
ALTER TABLE `activity_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `city_categories`
--
ALTER TABLE `city_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `continents`
--
ALTER TABLE `continents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `explore`
--
ALTER TABLE `explore`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `explore_images`
--
ALTER TABLE `explore_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `general_policies`
--
ALTER TABLE `general_policies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `order_log`
--
ALTER TABLE `order_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_log`
--
ALTER TABLE `payment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `profile_country`
--
ALTER TABLE `profile_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT for table `reviews_images`
--
ALTER TABLE `reviews_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subcategory`
--
ALTER TABLE `subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
