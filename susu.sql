-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 27, 2024 lúc 05:08 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `susu`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `binhluan`
--

CREATE TABLE `binhluan` (
  `id_bl` int(11) NOT NULL,
  `id_sp` int(11) DEFAULT NULL,
  `id_kh` int(11) DEFAULT NULL,
  `NoiDung` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `Hinh_BL` text DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_hdb`
--

CREATE TABLE `ct_hdb` (
  `id_cthdb` int(11) NOT NULL,
  `id_hdb` int(11) DEFAULT NULL,
  `id_sp` int(11) DEFAULT NULL,
  `id_dv` int(11) DEFAULT NULL,
  `SoLuong` float DEFAULT NULL,
  `DonGia` float DEFAULT NULL,
  `ThanhTien` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_hdn`
--

CREATE TABLE `ct_hdn` (
  `id_cthdn` int(11) NOT NULL,
  `id_hdn` int(11) DEFAULT NULL,
  `id_sp` int(11) DEFAULT NULL,
  `id_dv` int(11) DEFAULT NULL,
  `SoLuong` float DEFAULT NULL,
  `DonGia` float DEFAULT NULL,
  `ThanhTien` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ct_km`
--

CREATE TABLE `ct_km` (
  `id_CTKM` int(11) DEFAULT NULL,
  `id_km` int(11) DEFAULT NULL,
  `id_sp` int(11) DEFAULT NULL,
  `id_dm` int(11) DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `GiaTri` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `id_dm` int(11) NOT NULL,
  `Ten_dm` varchar(100) DEFAULT NULL,
  `parent_dm` int(11) DEFAULT NULL,
  `Hinh_dm` varchar(255) DEFAULT NULL,
  `Hoatdong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`id_dm`, `Ten_dm`, `parent_dm`, `Hinh_dm`, `Hoatdong`, `created_at`) VALUES
(102, 'Thế Giới Sữa', 0, NULL, 0, '2024-11-24 17:47:49'),
(103, 'Sữa Bột Cho Bé', 102, 'sua-bot-150x150-150x150-1.png', 0, '2024-11-24 17:48:42'),
(104, 'Sữa Tươi', 102, 'sua-tuoi-150x150-150x150-1.png', 0, '2024-11-24 17:49:38'),
(105, 'Sữa Pha Sẵn Cho Bé', 102, 'sua-pha-san-150x150-150x150.png', 0, '2024-11-24 17:50:00'),
(107, 'Bỉm, Tả', 0, NULL, 0, '2024-11-24 17:51:32'),
(108, 'Cho Bé 1 -6 tháng tuổi', 107, 'be-0-6-thang-tuoi-2.png', 0, '2024-11-24 17:51:57'),
(109, 'Thực Phẩm - Đồ Uống', 0, NULL, 0, '2024-11-24 17:52:46'),
(110, 'Phát Triển Chiều Cao', 109, '1ec6c428063186f2bdc4c5f235ec8d7f.png', 0, '2024-11-24 17:53:37'),
(111, 'Tốt Cho Dạ Dày', 109, '2760e4e53f5d5c9b45ab9ad395f8703e.png', 0, '2024-11-24 17:57:48'),
(112, '123', 0, NULL, 0, '2024-11-26 20:16:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dongia`
--

CREATE TABLE `dongia` (
  `id_dg` int(11) NOT NULL,
  `id_sp` int(11) DEFAULT NULL,
  `id_dv` int(11) DEFAULT NULL,
  `GiaNhap` float DEFAULT NULL,
  `GiaBan` float DEFAULT NULL,
  `KhuyenMai_Fast` float DEFAULT NULL,
  `SoLuong` int(11) DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `dongia`
--

INSERT INTO `dongia` (`id_dg`, `id_sp`, `id_dv`, `GiaNhap`, `GiaBan`, `KhuyenMai_Fast`, `SoLuong`, `HoatDong`) VALUES
(25, 96, 2, 0, 29.999, 0, 0, 0),
(26, 97, 2, 0, 29.999, 0, 0, 0),
(27, 97, 4, 0, 29.999, 0, 0, 0),
(28, 98, 2, 0, 213.123, 0, 123123, 0),
(29, 98, 4, 0, 123.123, 0, 123123123, 0),
(30, 99, 4, 0, 222.222, 0, 22, 0),
(31, 100, 4, 0, 222.222, 0, 22, 0),
(32, 100, 2, 0, 22.222, 0, 222, 0),
(33, 101, 4, 0, 223, 0, 1123123, 0),
(34, 102, 2, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donvi`
--

CREATE TABLE `donvi` (
  `id_dv` int(11) NOT NULL,
  `Ten_dv` varchar(100) DEFAULT NULL,
  `parent_dv` int(11) DEFAULT NULL,
  `Hoatdong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `donvi`
--

INSERT INTO `donvi` (`id_dv`, `Ten_dv`, `parent_dv`, `Hoatdong`, `created_at`) VALUES
(1, 'Thể tích sản phẩm', 0, 0, NULL),
(2, '100Ml', 1, 0, '2024-11-20 11:46:08'),
(4, '200Ml', 1, 0, '2024-11-22 16:16:31'),
(5, 'Size sản phẩm', 0, 0, '2024-11-22 19:05:41'),
(6, 'S', 5, 0, '2024-11-22 19:13:19'),
(8, 'XL', 5, 0, '2024-11-23 11:05:18'),
(9, 'XS', 5, 0, '2024-11-23 11:05:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hdb`
--

CREATE TABLE `hdb` (
  `id_hdb` int(11) NOT NULL,
  `id_kh` int(11) DEFAULT NULL,
  `id_nv` int(11) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `hdb`
--

INSERT INTO `hdb` (`id_hdb`, `id_kh`, `id_nv`, `TrangThai`, `created_at`) VALUES
(2, 3, 1, 1, '2024-11-20 22:47:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hdn`
--

CREATE TABLE `hdn` (
  `id_hdn` int(11) NOT NULL,
  `id_ncc` int(11) DEFAULT NULL,
  `id_nv` int(11) DEFAULT NULL,
  `TrangThai` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hinhsp`
--

CREATE TABLE `hinhsp` (
  `id_hinhsp` int(11) NOT NULL,
  `id_sp` int(11) NOT NULL,
  `Hinh_ChiTiet` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `id_kh` int(11) NOT NULL,
  `Ten_kh` varchar(180) DEFAULT NULL,
  `Email_kh` varchar(180) DEFAULT NULL,
  `NgaySinh_kh` date DEFAULT NULL,
  `SDT_kh` text DEFAULT NULL,
  `Hinh_kh` varchar(200) DEFAULT NULL,
  `Mk_kh` varchar(64) DEFAULT NULL,
  `Diem` float DEFAULT NULL,
  `Authen_kh` varchar(255) DEFAULT NULL,
  `Token_kh` varchar(255) DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`id_kh`, `Ten_kh`, `Email_kh`, `NgaySinh_kh`, `SDT_kh`, `Hinh_kh`, `Mk_kh`, `Diem`, `Authen_kh`, `Token_kh`, `HoatDong`, `created_at`) VALUES
(3, 'Lê Thanh Phát', 'dinaliw919@ipniel.com', '2004-02-12', '', NULL, '$2y$10$aaAB.Is3s46Ymztun4ezVu4wWmVcBD6UQRPxdLuKjcH3IRe.at0n6', NULL, '', '0cfdb43b5838c36361b71a9dd15fd737b2667bb695afbf16c8aa2ae3e227f7debc19284d708b064c3f77bdb34221cf0781ef', 1, '2024-11-27 08:41:40'),
(4, '123', 'dinaliw919123@ipniel.com', '2000-02-20', '0707002156', NULL, '$2y$10$GUlTvn3riY/OpcMar9EvB..PPemy7LHDAuA9MXaniGt0UVKdzMTl2', NULL, NULL, '2935b1a13b41d8fa66923f52ea629121e456d21ac8545bc2985cfa331e1797323d01ce3cc41c522eea3e6628d09d78a659cc', 0, '2024-11-27 08:47:08'),
(5, 'Lia', 'dinaliw919y@ipniel.com', '2024-12-06', '0707002396', NULL, '$2y$10$ZZ3yMJEMQ20TUjFDxdfXSu6axBOY4dfGeKXBWolDi9DQlqYchQKjq', NULL, NULL, 'd253ebd954f5616b56ff4b8021ade847eb1848e578d61d01ff69574154196b3f9372ba21e74ba8cdb696336cf435b1333599', 0, '2024-11-27 08:53:03'),
(6, 'Lia', 'dinaliw911239@ipniel.com', '2004-02-12', '07037002356', NULL, '$2y$10$mxmHDSR8OQozlYMO2BmLh.Ja5sRa6k09DvaIpB6I.fLRgBnAG8zwC', NULL, NULL, '78af5d0102da16642be371d02e23bde56fedadba88806bf319c3df9fe8b233736726ac2cff5bdedc9b1f9485aedea76930ce', 0, '2024-11-27 18:31:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khuyenmai`
--

CREATE TABLE `khuyenmai` (
  `id_km` int(11) NOT NULL,
  `Ten_kh` varchar(255) DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL,
  `Ngay_BD` datetime DEFAULT NULL,
  `Ngay_KT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaitintuc`
--

CREATE TABLE `loaitintuc` (
  `id_ltt` int(11) NOT NULL,
  `Ten_ltt` varchar(100) DEFAULT NULL,
  `parent_ltt` int(11) DEFAULT NULL,
  `Hinh_ltt` varchar(255) DEFAULT NULL,
  `Hoatdong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `loaitintuc`
--

INSERT INTO `loaitintuc` (`id_ltt`, `Ten_ltt`, `parent_ltt`, `Hinh_ltt`, `Hoatdong`, `created_at`) VALUES
(1, 'Sức khỏe13', 0, '1.png', 0, NULL),
(3, 'Cuộc Sống', 1, '1ec6c428063186f2bdc4c5f235ec8d7f.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `id_ncc` int(11) NOT NULL,
  `Ten_ncc` varchar(100) DEFAULT NULL,
  `parent_ncc` int(11) DEFAULT NULL,
  `Hinh_ncc` varchar(255) DEFAULT NULL,
  `Hoatdong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhacungcap`
--

INSERT INTO `nhacungcap` (`id_ncc`, `Ten_ncc`, `parent_ncc`, `Hinh_ncc`, `Hoatdong`, `created_at`) VALUES
(8, 'Vilamilk', NULL, 'ncc_1732435924.png', 0, '2024-11-24 14:57:32'),
(9, 'Nutifood', NULL, '6742dcb11583b-Nutifood-200x120.png', 0, '2024-11-24 14:58:41'),
(10, 'ColosBaBy', NULL, '6742dff13d806-colosbaby-200x120.png', 0, '2024-11-24 15:12:33'),
(11, 'AbbottGrow', NULL, '6742e0036ba70-AbbottGrow-200x120.png', 0, '2024-11-24 15:12:51'),
(12, 'Mẹ NỘi Địa', NULL, '6742f55428d71-meiji-noi-dia-200x120-1.png', 0, '2024-11-24 16:43:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id_nv` int(11) NOT NULL,
  `Ten_nv` varchar(180) DEFAULT NULL,
  `Email_nv` varchar(180) DEFAULT NULL,
  `NgaySinh_nv` date DEFAULT NULL,
  `Hinh_nv` varchar(200) DEFAULT NULL,
  `SDT_nv` text DEFAULT NULL,
  `Mk_nv` varchar(64) DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`id_nv`, `Ten_nv`, `Email_nv`, `NgaySinh_nv`, `Hinh_nv`, `SDT_nv`, `Mk_nv`, `HoatDong`, `created_at`) VALUES
(1, 'Lia Đẹp Trai Quá', 'dinaliw919@ipniel.com123', '2004-02-02', NULL, '0763232108', '$2y$10$X.0sJ7qII6t3zx6SoTTDWOfqsmvHF9NUnVbFZyc61WlvAPkiL5YU.', 1, '2024-11-27 21:58:03'),
(2, 'Nguyễn Mạnh Trường', 'truong@gmail.com', '2003-02-02', NULL, '0707002326', '$2y$10$QFcu1Jn2KOPnlj44PBFxt.MoAbmzPnK8RdqChXFxeS5fufDmJCy/.', 0, '2024-11-27 22:04:53'),
(3, 'THái Hoàng Minh Thông', 'dinaliw919@ipniel.com', '2004-02-02', NULL, '0707002336', '$2y$10$OwHn.WQrncmcPkNTvB3kcOp3.rHNJ8LwZO0i6DJBl/Es7czd/dN6.', 0, '2024-11-27 22:25:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `id_sp` int(11) NOT NULL,
  `id_dm` int(11) DEFAULT NULL,
  `id_xx` int(11) DEFAULT NULL,
  `id_ncc` int(11) DEFAULT NULL,
  `Ten_sp` varchar(255) DEFAULT NULL,
  `MoTa_sp` text DEFAULT NULL,
  `Hinh_Nen` varchar(255) DEFAULT NULL,
  `Hinh_ChiTiet` text DEFAULT NULL,
  `HoatDong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id_sp`, `id_dm`, `id_xx`, `id_ncc`, `Ten_sp`, `MoTa_sp`, `Hinh_Nen`, `Hinh_ChiTiet`, `HoatDong`) VALUES
(97, 103, 1, 8, 'Sữa bột Enfagrow A+ Neuropro số 4 vị nhạt dễ uống 1.7 kg (1 - 6 tháng)', '<h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">MFGM kết hợp 2\'-FL HMO hỗ trợ tăng sức đề kháng</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">2\'- FL HMO là dưỡng chất được tìm thấy nhiều trong sữa mẹ, đóng vai trò cân bằng hệ vi sinh đường ruột, tạo điều kiện cho lợi khuẩn phát triển, đồng thời kìm hãm các vi khuẩn gây hại, bảo vệ hệ tiêu hóa của trẻ luôn khỏe mạnh, hạn chế các bệnh về tiêu hóa thường gặp ở trẻ nhỏ như tiêu chảy, đầy hơi, khó tiêu,...</p><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><a href=\"https://www.avakids.com/sua-bot-cho-be\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; text-decoration: none; transition: 0.3s; color: rgb(47, 128, 237); font-size: 16px !important; cursor: inherit;\" target=\"_blank\" title=\"Xem thêm sữa bột đang kinh doanh taị AVAKids\">Sữa bột</a> với công thức chứa bộ đôi MFGM và 2\'-FL HMO giúp cải thiện khả năng miễn dịch và tạo nền tảng đề kháng vững vàng cho bé yêu.</p><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><img alt=\"HMO - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" data-src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024653.jpg\" title=\"HMO - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024653.jpg\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; border: 0px; max-width: calc(100% + 20px); height: auto !important;\"></p><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Chất xơ FOS &amp; 2\'-FL HMO hỗ trợ hệ tiêu hoá khoẻ mạnh</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Chất xơ hòa tan FOS trong sữa bột giúp bổ sung chất xơ thiếu hụt trong chế độ ăn của trẻ.&nbsp;<a href=\"https://www.avakids.com/sua-bot-cho-be-enfa\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; text-decoration: none; transition: 0.3s; color: rgb(47, 128, 237); font-size: 16px !important; cursor: inherit;\" title=\"Xem thêm sữa bột Enfa đang kinh doanh tại AVAKids\" type=\"Xem thêm sữa bột Enfa đang kinh doanh tại AVAKids\">Sữa bột Enfa</a> với hệ chất xơ FOS &amp; 2\'-FL HMO giúp hỗ trợ tiêu hóa, giúp phát triển lợi khuẩn trong toàn bộ ruột già, cải thiện sức khỏe đường ruột, hỗ trợ tiêu hóa và làm mềm phân.</p><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><img alt=\"FOS - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" data-src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024710.jpg\" title=\"FOS - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024710.jpg\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; border: 0px; max-width: calc(100% + 20px); height: auto !important;\"></p><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Sữa không chứa đường sucrose, thích hợp dành cho trẻ 2 - 6 tuổi</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Sữa bột Enfagrow A+ Neuropro không chứa đường sucrose, thích hợp dành cho trẻ từ 2 đến 6 tuổi, giúp trẻ hạn chế nguy cơ béo phì và sâu răng.</p><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><img alt=\"Độ tuổi - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" data-src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024729.jpg\" title=\"Độ tuổi - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024729.jpg\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; border: 0px; max-width: calc(100% + 20px); height: auto !important;\"></h3><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Dòng sữa chất lượng cao nhập khẩu trực tiếp từ Thái Lan</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><a href=\"https://www.avakids.com/sua-bot-cho-be-phat-trien-toan-dien\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; text-decoration: none; transition: 0.3s; color: rgb(47, 128, 237); font-size: 16px !important; cursor: inherit;\" target=\"_blank\" title=\"Xem thêm sữa phát triển toàn diện cho bé đang kinh doanh tại AVAKids\">Sữa phát triển toàn diện cho bé</a> thuộc thương hiệu Enfa - Mỹ, sản xuất tại Thái Lan, đảm bảo an toàn, chất lượng, chính hãng.</p><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><img alt=\"Nơi sản xuất - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" data-src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024747.jpg\" title=\"Nơi sản xuất - Sữa bột Enfagrow A+ Neuropro số 4 1.7 kg (2 - 6 tuổi)\" src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-190923-024747.jpg\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; border: 0px; max-width: calc(100% + 20px); height: auto !important;\"></p><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Thành phần dinh dưỡng</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><img alt=\"Thành phần dinh dưỡng\" data-src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-300822-105335.jpg\" title=\"Thành phần dinh dưỡng\" src=\"https://cdn.tgdd.vn/Products/Images/9079/260225/sua-bot-enfagrow-a-neuropro-4-vi-thanh-mat-1700g-300822-105335.jpg\" style=\"margin: 0px; padding: 0px; box-sizing: border-box; border: 0px; max-width: calc(100% + 20px); height: auto !important;\"></p><h3 style=\"margin: 0px 0px 15px; padding: 0px; box-sizing: border-box; font: bold 16px / 24px Helvetica, Arial, sans-serif; color: rgb(34, 34, 34); outline: none; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Lượng sữa khuyên dùng dành cho sữa bột Enfagrow A+ Neuropro số 4 vị nhạt dễ uống 1.7 kg (2 - 6 tuổi)</h3><p style=\"margin: 0px 0px 10px; padding: 0px; box-sizing: border-box; margin-block: 0px; margin-inline: 0px; text-rendering: geometricprecision; line-height: 1.5; font-size: 16px; color: rgb(34, 34, 34); font-family: Helvetica, Arial, sans-serif; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Thông tin trong bảng hướng dẫn pha chỉ là mức đề xuất trung bình, tùy chỉnh lượng sử dụng và số lần dùng theo sự phát triển và nhu cầu riêng biệt của trẻ.</p>', '4beJmTqpE6lNSyA8OXMovu3EvY5fuPFdKK6Mt2OKyQsc3KeYbCspdd-cong-thuc-growplus-colos-im.jpg', '72jNvx5MtTYNLYrOIxK4HDQECz9mrtouLcsMdq8ruK502eAf0jspdd-cong-thuc-growplus-colos-im (3).jpg,qFzorflpTAG0pCvAbLECiIlMaXjovWJ3F5YRhNaRwv4yE1pGchspdd-cong-thuc-growplus-colos-im (2).jpg,Qzofp9qZlg7cJ99JYMf1CSw7vHy76yHTSjxklJ3h9TYOQbm9H8spdd-cong-thuc-growplus-colos-im (1).jpg,xvSL2jopMZiOftkklNfYcv072WFSZfUTLQtlpvJduEhHC3gpB0spdd-cong-thuc-growplus-colos-im (3).jpg,4BH35dPazYlmIlZELreInXjZVvVCVqhOFAAaF3IqEI1ynDjvF3spdd-cong-thuc-growplus-colos-im (2).jpg,5MfE7UfdejtCPqzRlWuNlnO4CAv7wQZNheMjfPAxmI1rW4xK9Mspdd-cong-thuc-growplus-colos-im (1).jpg', 0),
(98, 105, 2, 10, 'Phát', '<p>123123</p>', '6ME92bnn1vvJjtdP51z4CtV7IpGiZR35nOXQ60VG8WiND9xyX2spdd-cong-thuc-growplus-colos-im (3).jpg', 'DXeZLE9GGaMa4NFHAZVA2sHOFtqqQ7PuNvQyi21v6QVwwIRc6mspdd-cong-thuc-growplus-colos-im (3).jpg,jZkuW13XVU9Dbs8EPQGMY78bnhogKw0Pbw7wk6hlFR1fVarNOkspdd-cong-thuc-growplus-colos-im (1).jpg,oCde7S1jlC6T8IRZnaBkc8uuviRPGVuyUFu9DSSy25ahqObtG1spdd-cong-thuc-growplus-colos-im (3).jpg,Mz1mFsguSGG9v1yi72V7Rq5ngw97XilPrxF0dZElxSeXT3VObyspdd-cong-thuc-growplus-colos-im (1).jpg', 0),
(101, 103, 1, 8, '123123123123', '<p>123123</p>', 'UWEIttr1aHlrESdnVrnnYEVs3Oxo2HKBm5NNQY4ZsLVO1MuXSBspdd-cong-thuc-growplus-1-bac-lo.jpg', 'PTs1EEXVh2b9GwyRLu7MRhIPqilexsIRuexxqqoyBUThmT8Nanspdd-cong-thuc-growplus-1-bac-lo.jpg,uC4zaVh0AEgxV4t45fgLtaiduTIHRhWmxXwjOOYvWKGC7CJRrospdd-cong-thuc-growplus-colos-im (3).jpg,VsSSMo5pTqxrrGHTpJXTiRol0i9fltspD3xvR496Uc9TBt1gWnspdd-cong-thuc-growplus-colos-im (2).jpg,85UdWDUaidPBk7VleEXaOS5KKOzoOPTMo2GUSHi04jNXIVTxSuspdd-cong-thuc-growplus-colos-im (1).jpg,qhXVXr2O3gh7mTb9Q8gphhTMMX9WPy0NvMwxQb4jassCc8scEfspdd-cong-thuc-growplus-colos-im.jpg,9VQg8NPKDmf31b8Wcfg3Pq9VYpHIrI7dV5CTm1aQqOR9qwEaGMspdd-cong-thuc-growplus-1-bac-lo.jpg,8hdjKXwBrYBC4vhE4Zv7I2Cn2OFxxG56oOXkgqfopbvKWfS9fwspdd-cong-thuc-growplus-colos-im (3).jpg,fm66IgJz7kZHF5ckjH7gTVLV1oNaJ4FRVxviHDWFH13Rf84pInspdd-cong-thuc-growplus-colos-im (2).jpg,uhGUxjbOgQgLsaS0tJr0MVqzHRIi1EQg0HqTsIuxDbkfbNa2hJspdd-cong-thuc-growplus-colos-im (1).jpg,JOoqpPZzQIw89pmatSHxx7Tipx8wVC4vv02SUmGtIPBrXIU7pfspdd-cong-thuc-growplus-colos-im.jpg', 0),
(102, 103, 1, 8, 'ádasd', '<p><strong>bbbbbbbbb</strong></p>', 'c461edSp05kT1LSR3HnPsT5BjPiQ8TaCJUTq4BMHOLGu93DIDDDone (1).gif', '0DW3FZ333aJhadIQrVlxEuJpO9Ny2RBd5cUwQP8Mc91emkOHgZMỪNG.png,Zq6y3w4CTVx6ZDwZ3Kxs4PYYmRYfYwKRdsHkZKrL48osVHixDrMỪNG.png', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tintuc`
--

CREATE TABLE `tintuc` (
  `id_tt` int(11) NOT NULL,
  `id_ltt` int(11) DEFAULT NULL,
  `tag_sp` varchar(100) DEFAULT NULL,
  `NoiDung` text DEFAULT NULL,
  `Hinh_Nen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `xuatxu`
--

CREATE TABLE `xuatxu` (
  `id_xx` int(11) NOT NULL,
  `Ten_xx` varchar(100) DEFAULT NULL,
  `Hoatdong` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `xuatxu`
--

INSERT INTO `xuatxu` (`id_xx`, `Ten_xx`, `Hoatdong`, `created_at`) VALUES
(1, 'Việt Nam', 0, '2024-11-24 16:34:55'),
(2, 'Úc', 0, '2024-11-24 16:34:55'),
(3, 'Mỹ', 0, '2024-11-24 16:48:25');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`id_bl`),
  ADD KEY `id_kh` (`id_kh`),
  ADD KEY `id_sp` (`id_sp`);

--
-- Chỉ mục cho bảng `ct_hdb`
--
ALTER TABLE `ct_hdb`
  ADD PRIMARY KEY (`id_cthdb`),
  ADD KEY `id_hdb` (`id_hdb`),
  ADD KEY `id_sp` (`id_sp`),
  ADD KEY `id_dv` (`id_dv`);

--
-- Chỉ mục cho bảng `ct_hdn`
--
ALTER TABLE `ct_hdn`
  ADD PRIMARY KEY (`id_cthdn`),
  ADD KEY `id_hdn` (`id_hdn`),
  ADD KEY `id_dv` (`id_dv`),
  ADD KEY `id_sp` (`id_sp`);

--
-- Chỉ mục cho bảng `ct_km`
--
ALTER TABLE `ct_km`
  ADD KEY `id_km` (`id_km`),
  ADD KEY `id_dm` (`id_dm`),
  ADD KEY `id_sp` (`id_sp`);

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`id_dm`);

--
-- Chỉ mục cho bảng `dongia`
--
ALTER TABLE `dongia`
  ADD PRIMARY KEY (`id_dg`),
  ADD KEY `id_donvi` (`id_dv`),
  ADD KEY `id_sp` (`id_sp`);

--
-- Chỉ mục cho bảng `donvi`
--
ALTER TABLE `donvi`
  ADD PRIMARY KEY (`id_dv`);

--
-- Chỉ mục cho bảng `hdb`
--
ALTER TABLE `hdb`
  ADD PRIMARY KEY (`id_hdb`),
  ADD KEY `id_kh` (`id_kh`);

--
-- Chỉ mục cho bảng `hdn`
--
ALTER TABLE `hdn`
  ADD PRIMARY KEY (`id_hdn`),
  ADD KEY `id_ncc` (`id_ncc`);

--
-- Chỉ mục cho bảng `hinhsp`
--
ALTER TABLE `hinhsp`
  ADD PRIMARY KEY (`id_hinhsp`);

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`id_kh`);

--
-- Chỉ mục cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  ADD PRIMARY KEY (`id_km`);

--
-- Chỉ mục cho bảng `loaitintuc`
--
ALTER TABLE `loaitintuc`
  ADD PRIMARY KEY (`id_ltt`);

--
-- Chỉ mục cho bảng `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`id_ncc`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id_nv`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id_sp`),
  ADD KEY `id_dm` (`id_dm`);

--
-- Chỉ mục cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`id_tt`),
  ADD KEY `id_ltt` (`id_ltt`);

--
-- Chỉ mục cho bảng `xuatxu`
--
ALTER TABLE `xuatxu`
  ADD PRIMARY KEY (`id_xx`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id_bl` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ct_hdb`
--
ALTER TABLE `ct_hdb`
  MODIFY `id_cthdb` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ct_hdn`
--
ALTER TABLE `ct_hdn`
  MODIFY `id_cthdn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `id_dm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT cho bảng `dongia`
--
ALTER TABLE `dongia`
  MODIFY `id_dg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `donvi`
--
ALTER TABLE `donvi`
  MODIFY `id_dv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `hdb`
--
ALTER TABLE `hdb`
  MODIFY `id_hdb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `hdn`
--
ALTER TABLE `hdn`
  MODIFY `id_hdn` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hinhsp`
--
ALTER TABLE `hinhsp`
  MODIFY `id_hinhsp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `id_kh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `khuyenmai`
--
ALTER TABLE `khuyenmai`
  MODIFY `id_km` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `loaitintuc`
--
ALTER TABLE `loaitintuc`
  MODIFY `id_ltt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nhacungcap`
--
ALTER TABLE `nhacungcap`
  MODIFY `id_ncc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id_nv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id_sp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  MODIFY `id_tt` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `xuatxu`
--
ALTER TABLE `xuatxu`
  MODIFY `id_xx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_ibfk_1` FOREIGN KEY (`id_kh`) REFERENCES `khachhang` (`id_kh`),
  ADD CONSTRAINT `binhluan_ibfk_2` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`id_sp`);

--
-- Các ràng buộc cho bảng `ct_hdb`
--
ALTER TABLE `ct_hdb`
  ADD CONSTRAINT `ct_hdb_ibfk_1` FOREIGN KEY (`id_hdb`) REFERENCES `hdb` (`id_hdb`),
  ADD CONSTRAINT `ct_hdb_ibfk_2` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`id_sp`),
  ADD CONSTRAINT `ct_hdb_ibfk_3` FOREIGN KEY (`id_dv`) REFERENCES `donvi` (`id_dv`);

--
-- Các ràng buộc cho bảng `ct_hdn`
--
ALTER TABLE `ct_hdn`
  ADD CONSTRAINT `ct_hdn_ibfk_1` FOREIGN KEY (`id_hdn`) REFERENCES `hdn` (`id_hdn`),
  ADD CONSTRAINT `ct_hdn_ibfk_2` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`id_sp`),
  ADD CONSTRAINT `ct_hdn_ibfk_3` FOREIGN KEY (`id_dv`) REFERENCES `donvi` (`id_dv`),
  ADD CONSTRAINT `ct_hdn_ibfk_4` FOREIGN KEY (`id_sp`) REFERENCES `danhmuc` (`id_dm`);

--
-- Các ràng buộc cho bảng `ct_km`
--
ALTER TABLE `ct_km`
  ADD CONSTRAINT `ct_km_ibfk_1` FOREIGN KEY (`id_km`) REFERENCES `khuyenmai` (`id_km`),
  ADD CONSTRAINT `ct_km_ibfk_2` FOREIGN KEY (`id_dm`) REFERENCES `danhmuc` (`id_dm`),
  ADD CONSTRAINT `ct_km_ibfk_3` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`id_sp`);

--
-- Các ràng buộc cho bảng `dongia`
--
ALTER TABLE `dongia`
  ADD CONSTRAINT `dongia_ibfk_1` FOREIGN KEY (`id_dv`) REFERENCES `donvi` (`id_dv`),
  ADD CONSTRAINT `dongia_ibfk_2` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`id_sp`);

--
-- Các ràng buộc cho bảng `hdb`
--
ALTER TABLE `hdb`
  ADD CONSTRAINT `hdb_ibfk_1` FOREIGN KEY (`id_kh`) REFERENCES `khachhang` (`id_kh`);

--
-- Các ràng buộc cho bảng `hdn`
--
ALTER TABLE `hdn`
  ADD CONSTRAINT `hdn_ibfk_1` FOREIGN KEY (`id_ncc`) REFERENCES `nhacungcap` (`id_ncc`);

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`id_dm`) REFERENCES `danhmuc` (`id_dm`),
  ADD CONSTRAINT `sanpham_ibfk_3` FOREIGN KEY (`id_ncc`) REFERENCES `nhacungcap` (`id_ncc`);

--
-- Các ràng buộc cho bảng `tintuc`
--
ALTER TABLE `tintuc`
  ADD CONSTRAINT `tintuc_ibfk_1` FOREIGN KEY (`id_ltt`) REFERENCES `loaitintuc` (`id_ltt`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
