-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2025 at 02:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agri_certify`
--

-- --------------------------------------------------------

--
-- Table structure for table `coffee_grower_notifications`
--

CREATE TABLE `coffee_grower_notifications` (
  `id` int(11) NOT NULL,
  `grower_name` varchar(255) NOT NULL,
  `grower_code` varchar(100) NOT NULL,
  `grower_category` varchar(100) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `sub_county` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village_road` varchar(255) DEFAULT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `contract_1` text DEFAULT NULL,
  `contract_2` text DEFAULT NULL,
  `contract_3` text DEFAULT NULL,
  `liability_1` text DEFAULT NULL,
  `liability_2` text DEFAULT NULL,
  `liability_3` text DEFAULT NULL,
  `prepared_by_name` varchar(255) DEFAULT NULL,
  `prepared_by_designation` varchar(100) DEFAULT NULL,
  `prepared_by_signature` varchar(255) DEFAULT NULL,
  `prepared_by_stamp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffee_grower_notifications`
--

INSERT INTO `coffee_grower_notifications` (`id`, `grower_name`, `grower_code`, `grower_category`, `county`, `sub_county`, `ward`, `village_road`, `postal_address`, `email`, `mobile_number`, `contract_1`, `contract_2`, `contract_3`, `liability_1`, `liability_2`, `liability_3`, `prepared_by_name`, `prepared_by_designation`, `prepared_by_signature`, `prepared_by_stamp`) VALUES
(1, 'ted', '4512', 'cooperative', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', '00100', 'example@gmail.com', '0124658974', 'ghyj', 'jkiu', 'fghh', 'sderr', 'njjui', 'njjh', 'rex', 'jjj', 'nnj', ''),
(2, 'ted', '4512', 'cooperative', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', '00100', 'example@gmail.com', '0124658974', 'ghyj', 'jkiu', 'fghh', 'sderr', 'njjui', 'njjh', 'rex', 'jjj', 'nnj', '');

-- --------------------------------------------------------

--
-- Table structure for table `coffee_nursery_applications`
--

CREATE TABLE `coffee_nursery_applications` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `national_id_passport` varchar(255) DEFAULT NULL,
  `nature_of_application` enum('New','Renewal') NOT NULL,
  `county` varchar(100) DEFAULT NULL,
  `sub_county` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village_road` varchar(255) DEFAULT NULL,
  `nearest_public_institution` varchar(255) DEFAULT NULL,
  `land_registration_plot_no` varchar(100) DEFAULT NULL,
  `land_document` varchar(255) DEFAULT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `company_documents` varchar(255) DEFAULT NULL,
  `nursery_category` enum('Commercial','Private') NOT NULL,
  `application_date` date DEFAULT NULL,
  `signature` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffee_nursery_applications`
--

INSERT INTO `coffee_nursery_applications` (`id`, `applicant_name`, `national_id_passport`, `nature_of_application`, `county`, `sub_county`, `ward`, `village_road`, `nearest_public_institution`, `land_registration_plot_no`, `land_document`, `postal_address`, `email`, `telephone`, `company_documents`, `nursery_category`, `application_date`, `signature`, `created_at`) VALUES
(1, 'Elizabeth Muraya', NULL, 'New', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', NULL, NULL, NULL, 'Elizabeth muraya , A15 Wankan street ,001000  NAIROBI KENYA', 'murayaliz98@gmail.com', NULL, NULL, 'Private', '2025-05-30', 'Elizabeth Muraya', '2025-05-30 11:22:41'),
(2, 'jim', NULL, 'New', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', NULL, NULL, 'uploads/68500ab132fad_icon3.PNG', '23234', 'jim@gmail.com', NULL, NULL, 'Commercial', '2025-06-16', 'jim doe', '2025-06-16 12:14:41'),
(3, 'joseph', NULL, 'New', 'Nyeri', 'kieni east', 'gakawa', 'baguret', NULL, NULL, 'uploads/6864dddaf2124_23.jpg', '10100', 'joseph@gmail.com', NULL, NULL, 'Commercial', '2025-07-02', 'joseph', '2025-07-02 07:20:59'),
(4, 'jom', NULL, 'New', 'Nyeri', 'kieni east', 'Kabaru', 'jet', NULL, NULL, 'uploads/6864e24333ad2_12.jpg', '10100', 'jin@gmail.com', NULL, NULL, 'Commercial', '2025-07-02', 'jim', '2025-07-02 07:39:47'),
(5, 'baricho farmers', NULL, 'New', 'Nyeri', 'mathira east', 'karitina', 'kiagararu', NULL, NULL, 'uploads/686dfd28d7e2f_12.jpg', '10100', 'baricho@gmail.com', NULL, NULL, 'Commercial', '2025-07-09', 'barich', '2025-07-09 05:24:57');

-- --------------------------------------------------------

--
-- Table structure for table `coffee_roaster_applications`
--

CREATE TABLE `coffee_roaster_applications` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `registered_address` varchar(255) DEFAULT NULL,
  `building` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `town_city` varchar(100) DEFAULT NULL,
  `lr_no` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `incorporation_date` date DEFAULT NULL,
  `registration_no` varchar(100) DEFAULT NULL,
  `director1_name` varchar(255) DEFAULT NULL,
  `director1_address` varchar(255) DEFAULT NULL,
  `director1_occupation` varchar(100) DEFAULT NULL,
  `director2_name` varchar(255) DEFAULT NULL,
  `director2_address` varchar(255) DEFAULT NULL,
  `director2_occupation` varchar(100) DEFAULT NULL,
  `director3_name` varchar(255) DEFAULT NULL,
  `director3_address` varchar(255) DEFAULT NULL,
  `director3_occupation` varchar(100) DEFAULT NULL,
  `branch_postal_address` varchar(255) DEFAULT NULL,
  `branch_postal_code` varchar(20) DEFAULT NULL,
  `branch_building` varchar(100) DEFAULT NULL,
  `branch_street` varchar(100) DEFAULT NULL,
  `branch_town_city` varchar(100) DEFAULT NULL,
  `branch_lr_no` varchar(100) DEFAULT NULL,
  `branch_mobile_no` varchar(20) DEFAULT NULL,
  `branch_email` varchar(255) DEFAULT NULL,
  `declaration_date` date DEFAULT NULL,
  `declaration_director1_name` varchar(255) DEFAULT NULL,
  `declaration_director1_signature` varchar(255) DEFAULT NULL,
  `declaration_director2_name` varchar(255) DEFAULT NULL,
  `declaration_director2_signature` varchar(255) DEFAULT NULL,
  `declaration_director3_name` varchar(255) DEFAULT NULL,
  `declaration_director3_signature` varchar(255) DEFAULT NULL,
  `authorized_officer_name` varchar(255) DEFAULT NULL,
  `authorized_officer_address` varchar(255) DEFAULT NULL,
  `authorized_officer_mobile` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coffee_roaster_applications`
--

INSERT INTO `coffee_roaster_applications` (`id`, `applicant_name`, `postal_address`, `postal_code`, `registered_address`, `building`, `street`, `town_city`, `lr_no`, `mobile_no`, `email`, `company_name`, `incorporation_date`, `registration_no`, `director1_name`, `director1_address`, `director1_occupation`, `director2_name`, `director2_address`, `director2_occupation`, `director3_name`, `director3_address`, `director3_occupation`, `branch_postal_address`, `branch_postal_code`, `branch_building`, `branch_street`, `branch_town_city`, `branch_lr_no`, `branch_mobile_no`, `branch_email`, `declaration_date`, `declaration_director1_name`, `declaration_director1_signature`, `declaration_director2_name`, `declaration_director2_signature`, `declaration_director3_name`, `declaration_director3_signature`, `authorized_officer_name`, `authorized_officer_address`, `authorized_officer_mobile`) VALUES
(1, 'tim', 'A12 lake', '00100', 'tgujn', 'mifugo house', 'powel', 'Nyeri', '5', '07123654756', 'tim3@gmail.com', 'roaters', '2025-06-09', 'cr/5/2025', 'ted', 'A12 VGYH', 'KML', 'red', 'A34 gyj', 'njujj', 'mat', 'A65 bnjj', 'mjkkk', '46 nyeri', '00100', 'business center', 'powwe', 'nyeri', '4', '014585755694', 'mat2@gmail.com', '2025-06-09', 'tito', 'ttt', 'andi', 'gjj', 'jkkk', 'lll', 'jim', 'A12 Nyeri', '0112547896'),
(2, 'tim', 'A12 lake', '00100', 'tgujn', 'mifugo house', 'powel', 'Nyeri', '5', '07123654756', 'tim3@gmail.com', 'roaters', '2025-06-09', 'cr/5/2025', 'ted', 'A12 VGYH', 'KML', 'red', 'A34 gyj', 'njujj', 'mat', 'A65 bnjj', 'mjkkk', '46 nyeri', '00100', 'business center', 'powwe', 'nyeri', '4', '014585755694', 'mat2@gmail.com', '2025-06-09', 'tito', 'ttt', 'andi', 'gjj', 'jkkk', 'lll', 'jim', 'A12 Nyeri', '0112547896');

-- --------------------------------------------------------

--
-- Table structure for table `commercial_coffee_milling_applications`
--

CREATE TABLE `commercial_coffee_milling_applications` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `application_type` enum('New','Renewal') NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `sub_county` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village_road` varchar(255) DEFAULT NULL,
  `lr_plot_no` varchar(100) DEFAULT NULL,
  `certificate_incorporation` varchar(255) DEFAULT NULL,
  `directors_list` varchar(255) DEFAULT NULL,
  `insurance_policy` varchar(255) DEFAULT NULL,
  `declaration_name_1` varchar(255) DEFAULT NULL,
  `declaration_signature_1` varchar(255) DEFAULT NULL,
  `declaration_date_1` date DEFAULT NULL,
  `declaration_name_2` varchar(255) DEFAULT NULL,
  `declaration_signature_2` varchar(255) DEFAULT NULL,
  `declaration_date_2` date DEFAULT NULL,
  `declaration_name_3` varchar(255) DEFAULT NULL,
  `declaration_signature_3` varchar(255) DEFAULT NULL,
  `declaration_date_3` date DEFAULT NULL,
  `parchment_capacity_tph` decimal(5,2) DEFAULT NULL,
  `buni_capacity_tph` decimal(5,2) DEFAULT NULL,
  `mill_certification` text DEFAULT NULL,
  `final_signature` varchar(255) DEFAULT NULL,
  `final_declaration_date` date DEFAULT NULL,
  `final_stamp_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commercial_coffee_milling_applications`
--

INSERT INTO `commercial_coffee_milling_applications` (`id`, `applicant_name`, `application_type`, `postal_address`, `postal_code`, `email`, `mobile_number`, `county`, `sub_county`, `ward`, `village_road`, `lr_plot_no`, `certificate_incorporation`, `directors_list`, `insurance_policy`, `declaration_name_1`, `declaration_signature_1`, `declaration_date_1`, `declaration_name_2`, `declaration_signature_2`, `declaration_date_2`, `declaration_name_3`, `declaration_signature_3`, `declaration_date_3`, `parchment_capacity_tph`, `buni_capacity_tph`, `mill_certification`, `final_signature`, `final_declaration_date`, `final_stamp_path`) VALUES
(1, 'Elizabeth Muraya', 'New', 'Elizabeth muraya , A15 Wankan street ,001000  NAIROBI KENYA', '00100 NAIROBI KENYA', 'murayaliz98@gmail.com', '0110829330', 'Nyeri', 'Kieni east', 'Kabaru', 'kabaru', '4', 'uploads/23.jpg', 'uploads/24.jpg', 'uploads/Agri.PNG', 'Elizabeth Muraya', 'tyj', '2025-06-09', 'jmo[kmnn', 'hhh', '2025-06-09', 'jjjbnb', '', '2025-06-09', 0.04, 0.04, '', '', '2025-06-09', 'uploads/33.jpg'),
(2, 'Elizabeth Muraya', 'New', 'Elizabeth muraya , A15 Wankan street ,001000  NAIROBI KENYA', '00100 NAIROBI KENYA', 'murayaliz98@gmail.com', '0110829330', 'Nyeri', 'Kieni east', 'Kabaru', 'kabaru', '4', 'uploads/23.jpg', 'uploads/24.jpg', 'uploads/Agri.PNG', 'Elizabeth Muraya', 'tyj', '2025-06-09', 'jmo[kmnn', 'hhh', '2025-06-09', 'jjjbnb', '', '2025-06-09', 0.04, 0.04, '', '', '2025-06-09', 'uploads/33.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `grow_millers_licence`
--

CREATE TABLE `grow_millers_licence` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `application_type` enum('New','Renewal') NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `sub_county` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village_road` varchar(255) DEFAULT NULL,
  `plot_no` varchar(100) DEFAULT NULL,
  `certificate_of_incorporation` varchar(255) DEFAULT NULL,
  `list_of_directors` varchar(255) DEFAULT NULL,
  `signatory1_name` varchar(255) DEFAULT NULL,
  `signatory1_signature` varchar(255) DEFAULT NULL,
  `signatory1_date` date DEFAULT NULL,
  `signatory2_name` varchar(255) DEFAULT NULL,
  `signatory2_signature` varchar(255) DEFAULT NULL,
  `signatory2_date` date DEFAULT NULL,
  `capacity_parchment` decimal(5,2) DEFAULT NULL,
  `capacity_buni` decimal(5,2) DEFAULT NULL,
  `mill_certification` varchar(255) DEFAULT NULL,
  `purpose_milling_own_coffee` tinyint(1) DEFAULT 0,
  `purpose_marketing_own_coffee` tinyint(1) DEFAULT 0,
  `purpose_roasting_packaging` tinyint(1) DEFAULT 0,
  `applicant_signature` varchar(255) DEFAULT NULL,
  `declaration_date` date DEFAULT NULL,
  `stamp` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grow_millers_licence`
--

INSERT INTO `grow_millers_licence` (`id`, `applicant_name`, `application_type`, `postal_address`, `postal_code`, `email`, `mobile_number`, `county`, `sub_county`, `ward`, `village_road`, `plot_no`, `certificate_of_incorporation`, `list_of_directors`, `signatory1_name`, `signatory1_signature`, `signatory1_date`, `signatory2_name`, `signatory2_signature`, `signatory2_date`, `capacity_parchment`, `capacity_buni`, `mill_certification`, `purpose_milling_own_coffee`, `purpose_marketing_own_coffee`, `purpose_roasting_packaging`, `applicant_signature`, `declaration_date`, `stamp`, `submitted_at`) VALUES
(1, 'ted', 'New', '345rio', '001000', 'example@gmail.com', '01236547896', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', '4', 'uploads/1749620779_mobile application assignment 1.pdf', 'uploads/1749620779_machine learning assignment 1.pdf', 'hino', 'uploads/1749620779_data.cvs.xlsx', '2025-06-11', 'frtg', NULL, '2025-06-11', 0.40, 0.40, '', 1, 0, 0, 'uploads/1749620779_microprossesor.docx', '2025-06-11', 'uploads/1749620779_realtime systems.docx', '2025-06-11 05:46:19'),
(2, 'ted', 'New', '345rio', '001000', 'example@gmail.com', '01236547896', 'Nyeri', 'kieni east', 'Kabaru', 'munyu', '4', 'uploads/1749620940_mobile application assignment 1.pdf', 'uploads/1749620940_machine learning assignment 1.pdf', 'hino', 'uploads/1749620940_data.cvs.xlsx', '2025-06-11', 'frtg', NULL, '2025-06-11', 0.40, 0.40, '', 1, 0, 0, 'uploads/1749620940_microprossesor.docx', '2025-06-11', 'uploads/1749620940_realtime systems.docx', '2025-06-11 05:49:00');

-- --------------------------------------------------------

--
-- Table structure for table `pulping_station_applications`
--

CREATE TABLE `pulping_station_applications` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `county` varchar(100) DEFAULT NULL,
  `sub_county` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `village_road` varchar(255) DEFAULT NULL,
  `nearest_institution` varchar(255) DEFAULT NULL,
  `proposed_farm_name` varchar(255) DEFAULT NULL,
  `lr_no` varchar(100) DEFAULT NULL,
  `supporting_document` varchar(255) DEFAULT NULL,
  `certificate_of_incorporation` varchar(255) DEFAULT NULL,
  `directors_list` varchar(255) DEFAULT NULL,
  `cert_name1` varchar(255) DEFAULT NULL,
  `cert_signature1` varchar(255) DEFAULT NULL,
  `cert_date1` date DEFAULT NULL,
  `cert_name2` varchar(255) DEFAULT NULL,
  `cert_signature2` varchar(255) DEFAULT NULL,
  `cert_date2` date DEFAULT NULL,
  `cert_name3` varchar(255) DEFAULT NULL,
  `cert_signature3` varchar(255) DEFAULT NULL,
  `cert_date3` date DEFAULT NULL,
  `land_acreage` decimal(6,2) DEFAULT NULL,
  `number_of_trees` int(11) DEFAULT NULL,
  `variety` varchar(255) DEFAULT NULL,
  `year1` int(11) DEFAULT NULL,
  `production1_kg` int(11) DEFAULT NULL,
  `year2` int(11) DEFAULT NULL,
  `production2_kg` int(11) DEFAULT NULL,
  `year3` int(11) DEFAULT NULL,
  `production3_kg` int(11) DEFAULT NULL,
  `recommendations` text DEFAULT NULL,
  `officer_name` varchar(255) DEFAULT NULL,
  `officer_designation` varchar(255) DEFAULT NULL,
  `officer_signature` varchar(255) DEFAULT NULL,
  `officer_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pulping_station_applications`
--

INSERT INTO `pulping_station_applications` (`id`, `applicant_name`, `postal_address`, `postal_code`, `email`, `telephone`, `county`, `sub_county`, `ward`, `village_road`, `nearest_institution`, `proposed_farm_name`, `lr_no`, `supporting_document`, `certificate_of_incorporation`, `directors_list`, `cert_name1`, `cert_signature1`, `cert_date1`, `cert_name2`, `cert_signature2`, `cert_date2`, `cert_name3`, `cert_signature3`, `cert_date3`, `land_acreage`, `number_of_trees`, `variety`, `year1`, `production1_kg`, `year2`, `production2_kg`, `year3`, `production3_kg`, `recommendations`, `officer_name`, `officer_designation`, `officer_signature`, `officer_date`) VALUES
(1, 'tim', '46 kiganjo', '011200', 'example@gmail.com', '01254789632', 'Nyeri', 'kieni east', 'ward', 'munyu', 'mifugo house', 'teejj', '5', 'uploads/1749541771_23.jpg', 'uploads/1749541771_13.jpg', 'uploads/1749541771_icon.PNG', 'ruin', 'rrr', '2025-06-10', 'tyhhh', 'jj', '2025-06-10', 'refd', 'jjn', '2025-06-10', 0.00, 7, 'kio', 7, 54, 8, 21, 6, 96, '', 'firm', 'jujkiii', 'loik', '2025-06-10');

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`id`, `full_name`, `email`, `phone_number`, `password`, `created_at`) VALUES
(0, 'Elizabeth Muraya', 'murayaelizabeth96@gmail.com', '0110829330', '$2y$10$yR/8P1PMsqlZypMT6m3VV.7oYwvrv7b6SNdlao7yQhEOnIa1vsGbm', '2025-05-29 08:10:19'),
(0, 'Job Iregi', 'example@gmail.com', '0790020279', '$2y$10$8rh87zs/y1M2qnt.z3PM3uTK0/y5VGI17TFcjD65VjHZZ0t16tYWu', '2025-05-29 08:38:09'),
(0, 'yasmin', 'yasmin@gmail.com', '1234567890', '$2y$10$JxNcL3sILxdTCJWSxElMGezn6y8K8mnIGCNR7vvaYva/4Gf6mTiuG', '2025-05-29 09:58:58'),
(0, 'jim', 'jim@gmail.com', '0790020278', '$2y$10$DTDUw8A7JE01ZCdGQDICKOsAJgONwj6QNfBD6gP8O1rVcSdZMQ3z.', '2025-06-16 12:11:33'),
(0, 'Examples', 'regs@gmail.com', '0745123698', '$2y$10$Bq9J.vmibwYJ/LXVSXldXu.G.9f4rc4sDdT7zodluL.tpRm6pH.eC', '2025-07-01 06:27:12'),
(0, 'baricho farmers cooperative', 'baricho@gmail.com', '089874899', '$2y$10$Xt59iv4qS2tIeqYhZT.07.eRAAkOtZID93bUvVXsrFzxZvcl5MNLi', '2025-07-09 05:21:26'),
(0, 'ian test', 'test@mail.com', '0712345678', '$2y$10$Kd1fAEb844NsPeSk02DJH.GLgdFDjjxPwyTVTITuc/taXqB56Kw5C', '2025-07-21 08:22:35'),
(0, 'Aricertify', 'Aricertify@gmail.com', '0125367895', '$2y$10$m1nZK1TIkGaAjv990lwKuOg3kpNBzqVX/Yt095aE1MkObM/RUms.q', '2025-08-01 08:17:27'),
(0, 'ted Kilimo', 'ted@gmail.com', '0752369841', '$2y$10$hkuMpp6AqVGfVmQU3I8NqOkV44MnyWKtSydJNtTjWINtYwoObkjt.', '2025-08-06 06:43:49'),
(0, 'Local Host', 'local@gmail.com', '0745123698', '$2y$10$NhkVNNdaInLzlsXshCBXTeNd6rwltQbfFPGMG1EoQqQNO08Wl1PfC', '2025-08-06 08:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_applications`
--

CREATE TABLE `warehouse_applications` (
  `id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `application_type` enum('New','Renewal') NOT NULL,
  `postal_address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `registered_office` varchar(255) DEFAULT NULL,
  `building` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `town_city` varchar(100) DEFAULT NULL,
  `lr_no` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `date_of_incorporation` date DEFAULT NULL,
  `registration_no` varchar(100) DEFAULT NULL,
  `director_a_name` varchar(255) DEFAULT NULL,
  `director_a_address` varchar(255) DEFAULT NULL,
  `director_a_occupation` varchar(100) DEFAULT NULL,
  `director_b_name` varchar(255) DEFAULT NULL,
  `director_b_address` varchar(255) DEFAULT NULL,
  `director_b_occupation` varchar(100) DEFAULT NULL,
  `director_c_name` varchar(255) DEFAULT NULL,
  `director_c_address` varchar(255) DEFAULT NULL,
  `director_c_occupation` varchar(100) DEFAULT NULL,
  `authorized_officer_name` varchar(255) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `stamp` text DEFAULT NULL,
  `subcounty` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse_applications`
--

INSERT INTO `warehouse_applications` (`id`, `applicant_name`, `application_type`, `postal_address`, `postal_code`, `registered_office`, `building`, `street`, `town_city`, `lr_no`, `mobile_number`, `email`, `date_of_incorporation`, `registration_no`, `director_a_name`, `director_a_address`, `director_a_occupation`, `director_b_name`, `director_b_address`, `director_b_occupation`, `director_c_name`, `director_c_address`, `director_c_occupation`, `authorized_officer_name`, `designation`, `signature`, `application_date`, `stamp`, `subcounty`, `ward`) VALUES
(1, 'Elizabeth Muraya', 'New', 'Elizabeth muraya , A15 Wankan street ,001000  NAIROBI KENYA', '00100 NAIROBI KENYA', 'Agriculture', 'mifugo house', 'powel street', 'nyeri', '4', '0110829330', 'murayaliz98@gmail.com', '2025-06-09', 'wh/4/2025', 'red', 'a13 powel', 'fgrcv', 'ted', 'gyy', 'scvgjmk', 'liam', 'gtf', 'kjbk', '', '', '', '0000-00-00', '', NULL, NULL),
(2, 'nick', 'New', '10011', '46kiganjo', 'Agriculture', 'mifugo house', 'kamakwaa', 'nyeri', '536', '0752136958', 'example@gmail.com', '2025-07-03', 'wh/4/2025', 'Nick', '11000', 'fgrcv', 'Tim', '01100', 'senoir', 'Liam', '011000', 'rhjf', 'jeff', 'bhj', 'jujf', '2025-07-03', 'eyehe', 'Kieni east', 'Kabaru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coffee_grower_notifications`
--
ALTER TABLE `coffee_grower_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffee_nursery_applications`
--
ALTER TABLE `coffee_nursery_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coffee_roaster_applications`
--
ALTER TABLE `coffee_roaster_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commercial_coffee_milling_applications`
--
ALTER TABLE `commercial_coffee_milling_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grow_millers_licence`
--
ALTER TABLE `grow_millers_licence`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pulping_station_applications`
--
ALTER TABLE `pulping_station_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_applications`
--
ALTER TABLE `warehouse_applications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coffee_grower_notifications`
--
ALTER TABLE `coffee_grower_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coffee_nursery_applications`
--
ALTER TABLE `coffee_nursery_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coffee_roaster_applications`
--
ALTER TABLE `coffee_roaster_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `commercial_coffee_milling_applications`
--
ALTER TABLE `commercial_coffee_milling_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `grow_millers_licence`
--
ALTER TABLE `grow_millers_licence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pulping_station_applications`
--
ALTER TABLE `pulping_station_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouse_applications`
--
ALTER TABLE `warehouse_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
ALTER TABLE `coffee_nursery_applications` 
ADD COLUMN `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
ADD COLUMN `officer_notes` TEXT,
ADD COLUMN `approved_by` INT,
ADD COLUMN `approved_at` TIMESTAMP NULL;

CREATE TABLE `officers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(255) NOT NULL,
  `role` ENUM('officer', 'admin') DEFAULT 'officer',
  `county` VARCHAR(100),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);