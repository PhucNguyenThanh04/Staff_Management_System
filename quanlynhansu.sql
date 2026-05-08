-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 01, 2026 lúc 05:47 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlynhansu`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendances`
--

CREATE TABLE `attendances` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `working_hours` decimal(4,2) DEFAULT 0.00,
  `work_point` decimal(3,2) DEFAULT 1.00,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `attendance_date`, `check_in`, `check_out`, `working_hours`, `work_point`, `note`, `created_at`, `updated_at`) VALUES
(1, 6, '2025-04-23', '10:38:00', '18:48:00', 6.67, 0.50, '', '2025-04-23 05:47:56', '2025-04-23 05:47:56'),
(3, 6, '2025-04-24', '08:53:00', '18:15:00', 7.87, 1.00, '', '2025-04-23 12:52:47', '2025-04-23 12:52:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `contract_type` tinyint(2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contracts`
--

INSERT INTO `contracts` (`id`, `name`, `employee_id`, `contract_type`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(4, '1212', 6, 1, '2025-04-19', NULL, '2025-04-19 05:38:33', '2025-04-19 05:38:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`) VALUES
(2, 'Kế toán', '2024-11-25 10:20:23', '2024-11-25 10:20:23'),
(3, 'Marketing', '2024-11-25 10:20:28', '2024-11-25 10:20:28'),
(4, 'IT', '2024-11-25 10:20:35', '2024-11-25 10:20:35'),
(5, 'Nhân sự', '2024-11-25 10:20:42', '2024-11-25 10:20:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `employees`
--

CREATE TABLE `employees` (
  `id` int(11) UNSIGNED NOT NULL,
  `department_id` int(10) DEFAULT NULL COMMENT 'phòng ban',
  `name` varchar(255) DEFAULT NULL COMMENT 'tên',
  `email` varchar(255) NOT NULL COMMENT 'email',
  `password` varchar(255) NOT NULL COMMENT 'mật khẩu',
  `role` tinyint(10) NOT NULL DEFAULT 5 COMMENT 'quyền của nhân viên',
  `birthday` date DEFAULT NULL COMMENT 'ngày sinh',
  `address` text DEFAULT NULL COMMENT 'địa chỉ',
  `phone_number` text DEFAULT NULL COMMENT 'sđt',
  `gender` varchar(10) DEFAULT NULL COMMENT 'giới tính',
  `cccd` varchar(20) DEFAULT NULL COMMENT 'số căn cước công dân',
  `position` varchar(255) DEFAULT NULL COMMENT 'vị trí làm việc',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1: đang làm việc, 0: nghỉ việc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `employees`
--

INSERT INTO `employees` (`id`, `department_id`, `name`, `email`, `password`, `role`, `birthday`, `address`, `phone_number`, `gender`, `cccd`, `position`, `status`, `created_at`, `updated_at`) VALUES
(9, 4, 'Admin', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 1, '1990-01-01', 'HCM', '0900000001', 'male', '111111111', 'Quản trị hệ thống', 1, '2026-04-30 14:41:08', '2026-04-30 14:41:08'),
(10, 5, 'HR', 'hr@gmail.com', '47ddc6a13343881b6de3dd7a48849f72', 2, '1995-01-01', 'HCM', '0900000002', 'female', '222222221', 'Nhân sự', 1, '2026-04-30 14:41:08', '2026-04-30 15:01:15'),
(11, 2, 'Kế toán', 'ketoan2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 3, '1992-01-01', 'HCM', '0900000003', 'female', '333333333', 'Kế toán', 1, '2026-04-30 14:41:08', '2026-04-30 14:41:08'),
(12, 5, 'Nhân viên', 'user@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 4, '2000-01-01', 'HCM', '0900000004', 'male', '444444444', 'Nhân viên', 1, '2026-04-30 14:41:08', '2026-04-30 14:41:08'),
(13, 0, 'dsdsasa', 'sasasa@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 2, '0000-00-00', '', '', 'male', '', 'Nhân sự', 1, '2026-04-30 15:04:24', '2026-04-30 15:04:24'),
(14, 0, 'nv', 'user1@gmail.com', 'fcea920f7412b5da7be0cf42b8c93759', 4, '0000-00-00', '', '', '', '', '', 1, '2026-04-30 15:06:33', '2026-05-01 03:46:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `base_salary` decimal(12,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payrolls`
--

INSERT INTO `payrolls` (`id`, `employee_id`, `base_salary`, `created_at`, `updated_at`) VALUES
(2, 6, 100000000.00, '2024-11-26 14:33:03', '2024-11-26 14:33:03'),
(3, 12, 1000000.00, '2026-04-30 15:07:48', '2026-04-30 15:07:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payroll_details`
--

CREATE TABLE `payroll_details` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL COMMENT 'nhân viên id',
  `payroll_id` int(11) NOT NULL COMMENT 'bảng lương id',
  `salary` decimal(12,2) DEFAULT 0.00 COMMENT 'lương',
  `base_salary` decimal(12,2) DEFAULT 0.00,
  `bonus` decimal(10,2) DEFAULT 0.00 COMMENT 'thưởng',
  `deductions` decimal(10,2) DEFAULT 0.00 COMMENT 'khấu trừ',
  `insurance` decimal(10,2) DEFAULT 0.00 COMMENT 'bảo hiểm',
  `net_salary` decimal(10,2) DEFAULT 0.00 COMMENT 'thực lĩnh',
  `payment_date` date DEFAULT NULL COMMENT 'ngày thanh toán',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payroll_month` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `payroll_details`
--

INSERT INTO `payroll_details` (`id`, `employee_id`, `payroll_id`, `salary`, `base_salary`, `bonus`, `deductions`, `insurance`, `net_salary`, `payment_date`, `created_at`, `updated_at`, `payroll_month`) VALUES
(9, 6, 2, 682.00, 10000.00, 0.00, 0.00, 0.00, 682.00, '2025-04-23', '2025-04-23 14:11:56', '2025-04-23 14:11:56', '2025-04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `payroll_details`
--
ALTER TABLE `payroll_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================
-- CHỨC NĂNG 8: Đánh giá & Khen thưởng / Kỷ luật
-- ============================================================

CREATE TABLE `evaluations` (
                               `id`          int(11) NOT NULL AUTO_INCREMENT,
                               `employee_id` int(11) NOT NULL COMMENT 'nhân viên được đánh giá',
                               `reviewer_id` int(11) DEFAULT NULL COMMENT 'người đánh giá (FK employees)',
                               `eval_type`   tinyint(2) NOT NULL COMMENT '1: tháng, 2: quý, 3: năm',
                               `period`      varchar(20) NOT NULL COMMENT 'ví dụ: 2025-04, 2025-Q2, 2025',
                               `eval_date`   date NOT NULL COMMENT 'ngày lập đánh giá',
                               `score`       decimal(4,2) DEFAULT NULL COMMENT 'điểm tổng (0–10)',
                               `content`     text DEFAULT NULL COMMENT 'nhận xét chung',
                               `strengths`   text DEFAULT NULL COMMENT 'điểm mạnh',
                               `weaknesses`  text DEFAULT NULL COMMENT 'điểm cần cải thiện',
                               `status`      varchar(20) NOT NULL DEFAULT 'draft'
                                   COMMENT 'draft | submitted | approved',
                               `created_at`  timestamp NOT NULL DEFAULT current_timestamp(),
                               `updated_at`  timestamp NOT NULL DEFAULT current_timestamp()
                                   ON UPDATE current_timestamp(),
                               PRIMARY KEY (`id`),
                               KEY `idx_eval_employee` (`employee_id`),
                               KEY `idx_eval_period`   (`period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `rewards_disciplines` (
                                       `id`           int(11) NOT NULL AUTO_INCREMENT,
                                       `employee_id`  int(11) NOT NULL COMMENT 'nhân viên liên quan',
                                       `approved_by`  int(11) DEFAULT NULL COMMENT 'người phê duyệt (FK employees)',
                                       `rd_type`      tinyint(2) NOT NULL
                 COMMENT '1: khen thưởng, 2: kỷ luật, 3: cảnh cáo, 4: sáng kiến',
                                       `title`        varchar(255) NOT NULL COMMENT 'tên khen thưởng / hình thức kỷ luật',
                                       `reason`       text DEFAULT NULL COMMENT 'lý do / căn cứ',
                                       `amount`       decimal(12,2) DEFAULT 0.00 COMMENT 'giá trị thưởng (nếu có)',
                                       `effective_date` date NOT NULL COMMENT 'ngày có hiệu lực',
                                       `status`       varchar(20) NOT NULL DEFAULT 'pending'
                                           COMMENT 'pending | approved | rejected',
                                       `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
                                       `updated_at`   timestamp NOT NULL DEFAULT current_timestamp()
                                           ON UPDATE current_timestamp(),
                                       PRIMARY KEY (`id`),
                                       KEY `idx_rd_employee` (`employee_id`),
                                       KEY `idx_rd_type`     (`rd_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- CHỨC NĂNG 9: Quản lý tài sản cấp phát
-- ============================================================

CREATE TABLE `assets` (
                          `id`            int(11) NOT NULL AUTO_INCREMENT,
                          `code`          varchar(50) NOT NULL UNIQUE COMMENT 'mã tài sản nội bộ',
                          `name`          varchar(255) NOT NULL COMMENT 'tên tài sản',
                          `category`      tinyint(2) NOT NULL
                  COMMENT '1: laptop, 2: điện thoại, 3: thẻ ra vào, 4: khác',
                          `brand`         varchar(100) DEFAULT NULL COMMENT 'hãng sản xuất',
                          `model`         varchar(100) DEFAULT NULL COMMENT 'model / phiên bản',
                          `serial_number` varchar(100) DEFAULT NULL COMMENT 'số serial',
                          `value`         decimal(15,2) DEFAULT 0.00 COMMENT 'giá trị mua',
                          `purchase_date` date DEFAULT NULL COMMENT 'ngày mua',
                          `status`        tinyint(2) NOT NULL DEFAULT 1
                  COMMENT '1: sẵn sàng, 2: đang cấp phát, 3: bảo trì, 4: thanh lý',
                          `note`          text DEFAULT NULL,
                          `created_at`    timestamp NOT NULL DEFAULT current_timestamp(),
                          `updated_at`    timestamp NOT NULL DEFAULT current_timestamp()
                              ON UPDATE current_timestamp(),
                          PRIMARY KEY (`id`),
                          KEY `idx_asset_status`   (`status`),
                          KEY `idx_asset_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `asset_assignments` (
                                     `id`            int(11) NOT NULL AUTO_INCREMENT,
                                     `asset_id`      int(11) NOT NULL COMMENT 'FK assets',
                                     `employee_id`   int(11) NOT NULL COMMENT 'FK employees — người nhận',
                                     `assigned_by`   int(11) DEFAULT NULL COMMENT 'FK employees — người cấp',
                                     `assign_date`   date NOT NULL COMMENT 'ngày bàn giao',
                                     `return_date`   date DEFAULT NULL COMMENT 'ngày trả (NULL = đang dùng)',
                                     `condition_out` tinyint(2) DEFAULT 1
                  COMMENT 'tình trạng khi cấp: 1: tốt, 2: trung bình, 3: kém',
                                     `condition_in`  tinyint(2) DEFAULT NULL
                  COMMENT 'tình trạng khi trả (NULL = chưa trả)',
                                     `note`          text DEFAULT NULL,
                                     `created_at`    timestamp NOT NULL DEFAULT current_timestamp(),
                                     `updated_at`    timestamp NOT NULL DEFAULT current_timestamp()
                                         ON UPDATE current_timestamp(),
                                     PRIMARY KEY (`id`),
                                     KEY `idx_aa_asset`    (`asset_id`),
                                     KEY `idx_aa_employee` (`employee_id`),
                                     KEY `idx_aa_active`   (`return_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- CHỨC NĂNG 10: Quản lý thông báo & truyền thông nội bộ
-- ============================================================

CREATE TABLE `notifications` (
                                 `id`           int(11) NOT NULL AUTO_INCREMENT,
                                 `title`        varchar(255) NOT NULL,
                                 `content`      text NOT NULL,
                                 `sender_id`    int(11) NOT NULL COMMENT 'người tạo thông báo',
                                 `target_type`  tinyint(2) NOT NULL DEFAULT 1
                     COMMENT '1: toàn bộ, 2: theo role, 3: theo phòng ban, 4: theo nhân viên',
                                 `target_value` varchar(50) DEFAULT NULL COMMENT 'id role/department/employee tùy target_type',
                                 `is_pinned`    tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: không ghim, 1: ghim',
                                 `is_active`    tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: ẩn, 1: hiển thị',
                                 `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
                                 `updated_at`   timestamp NOT NULL DEFAULT current_timestamp()
                                     ON UPDATE current_timestamp(),
                                 PRIMARY KEY (`id`),
                                 KEY `idx_n_target_type` (`target_type`),
                                 KEY `idx_n_sender` (`sender_id`),
                                 KEY `idx_n_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `notification_reads` (
                                      `id`              int(11) NOT NULL AUTO_INCREMENT,
                                      `notification_id` int(11) NOT NULL,
                                      `employee_id`     int(11) NOT NULL,
                                      `read_at`         datetime NOT NULL,
                                      `created_at`      timestamp NOT NULL DEFAULT current_timestamp(),
                                      PRIMARY KEY (`id`),
                                      UNIQUE KEY `uniq_notification_employee` (`notification_id`,`employee_id`),
                                      KEY `idx_nr_employee` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
