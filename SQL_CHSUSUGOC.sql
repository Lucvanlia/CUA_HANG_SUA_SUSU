CREATE TABLE `KhachHang` (
  `id_kh` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_kh` varchar(180),
  `Email_kh` varchar(180),
  `NgaySinh_kh` date,
  `Hinh_kh` varchar(200),
  `Mk_kh` varchar(64),
  `Diem` float,
  `Authen_kh` varchar(255),
  `Token_kh` varchar(255),
  `HoatDong` int,
  `created_at` datetime
);

CREATE TABLE `nhanvien` (
  `id_nv` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_nv` varchar(180),
  `Email_nv` varchar(180),
  `NgaySinh_nv` date,
  `Hinh_nv` varchar(200),
  `Mk_nv` varchar(64),
  `HoatDong` int,
  `created_at` datetime
);

CREATE TABLE `DanhMuc` (
  `id_dm` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_dm` varchar(100),
  `parent_dm` int,
  `Hinh_dm` varchar(255),
  `Hoatdong` int,
  `created_at` datetime
);

CREATE TABLE `DonVi` (
  `id_dv` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_dv` varchar(100),
  `parent_dv` int,
  `Hoatdong` int,
  `created_at` datetime
);

CREATE TABLE `XuatXu` (
  `id_xx` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_xx` varchar(100),
  `Hoatdong` int
);

CREATE TABLE `NhaCungCap` (
  `id_ncc` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_ncc` varchar(100),
  `parent_ncc` int,
  `Hinh_ncc` varchar(255),
  `Hoatdong` int,
  `created_at` datetime
);

CREATE TABLE `DonGia` (
  `id_dg` int PRIMARY KEY AUTO_INCREMENT,
  `id_sp` int,
  `id_donvi` int,
  `GiaNhap` float,
  `GiaBan` float,
  `KhuyenMai_Fast` float,
  `HoatDong` int
);

CREATE TABLE `SanPham` (
  `id_sp` int PRIMARY KEY AUTO_INCREMENT,
  `id_dm` int,
  `id_dv` int,
  `id_xx` int,
  `id_ncc` int,
  `Ten_sp` varchar(255),
  `Hinh_Nen` varchar(255),
  `Hinh_ChiTiet` text,
  `HoatDong` int
);

CREATE TABLE `HDB` (
  `id_hdb` int PRIMARY KEY AUTO_INCREMENT,
  `id_kh` int,
  `id_nv` int,
  `TrangThai` int,
  `created_at` datetime
);

CREATE TABLE `CT_HDB` (
  `id_cthdb` int PRIMARY KEY AUTO_INCREMENT,
  `id_hdb` int,
  `id_sp` int,
  `id_dv` int,
  `SoLuong` float,
  `DonGia` float,
  `ThanhTien` float
);

CREATE TABLE `HDN` (
  `id_hdn` int PRIMARY KEY AUTO_INCREMENT,
  `id_ncc` int,
  `id_nv` int,
  `TrangThai` int,
  `created_at` datetime
);

CREATE TABLE `CT_HDN` (
  `id_cthdn` int PRIMARY KEY AUTO_INCREMENT,
  `id_hdn` int,
  `id_sp` int,
  `id_dv` int,
  `SoLuong` float,
  `DonGia` float,
  `ThanhTien` float
);

CREATE TABLE `BinhLuan` (
  `id_bl` int PRIMARY KEY AUTO_INCREMENT,
  `id_sp` int,
  `id_kh` int,
  `NoiDung` varchar(255),
  `rating` int,
  `Hinh_BL` text,
  `HoatDong` int
);

CREATE TABLE `KhuyenMai` (
  `id_km` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_kh` varchar(255),
  `HoatDong` int,
  `Ngay_BD` datetime,
  `Ngay_KT` datetime
);

CREATE TABLE `CT_KM` (
  `id_CTKM` int,
  `id_km` int,
  `id_sp` int,
  `id_dm` int,
  `SoLuong` int,
  `GiaTri` float
);

CREATE TABLE `LoaiTinTuc` (
  `id_ltt` int PRIMARY KEY AUTO_INCREMENT,
  `Ten_ltt` varchar(100),
  `parent_ltt` int,
  `Hinh_ltt` varchar(255),
  `Hoatdong` int,
  `created_at` datetime
);

CREATE TABLE `TinTuc` (
  `id_tt` int PRIMARY KEY AUTO_INCREMENT,
  `id_ltt` int,
  `tag_sp` varchar(100),
  `NoiDung` text,
  `Hinh_Nen` varchar(255)
);

ALTER TABLE `SanPham` ADD FOREIGN KEY (`id_dm`) REFERENCES `DanhMuc` (`id_dm`);

ALTER TABLE `SanPham` ADD FOREIGN KEY (`id_dv`) REFERENCES `DonVi` (`id_dv`);

ALTER TABLE `SanPham` ADD FOREIGN KEY (`id_ncc`) REFERENCES `NhaCungCap` (`id_ncc`);

ALTER TABLE `DonGia` ADD FOREIGN KEY (`id_donvi`) REFERENCES `DonVi` (`id_dv`);

ALTER TABLE `DonGia` ADD FOREIGN KEY (`id_sp`) REFERENCES `SanPham` (`id_sp`);

ALTER TABLE `HDB` ADD FOREIGN KEY (`id_kh`) REFERENCES `KhachHang` (`id_kh`);

ALTER TABLE `CT_HDB` ADD FOREIGN KEY (`id_hdb`) REFERENCES `HDB` (`id_hdb`);

ALTER TABLE `CT_HDB` ADD FOREIGN KEY (`id_sp`) REFERENCES `SanPham` (`id_sp`);

ALTER TABLE `CT_HDB` ADD FOREIGN KEY (`id_dv`) REFERENCES `DonVi` (`id_dv`);

ALTER TABLE `SanPham` ADD FOREIGN KEY (`id_xx`) REFERENCES `XuatXu` (`id_xx`);

ALTER TABLE `HDN` ADD FOREIGN KEY (`id_ncc`) REFERENCES `NhaCungCap` (`id_ncc`);

ALTER TABLE `CT_HDN` ADD FOREIGN KEY (`id_hdn`) REFERENCES `HDN` (`id_hdn`);

ALTER TABLE `CT_HDN` ADD FOREIGN KEY (`id_sp`) REFERENCES `SanPham` (`id_sp`);

ALTER TABLE `CT_HDN` ADD FOREIGN KEY (`id_dv`) REFERENCES `DonVi` (`id_dv`);

ALTER TABLE `BinhLuan` ADD FOREIGN KEY (`id_kh`) REFERENCES `KhachHang` (`id_kh`);

ALTER TABLE `BinhLuan` ADD FOREIGN KEY (`id_sp`) REFERENCES `SanPham` (`id_sp`);

ALTER TABLE `CT_KM` ADD FOREIGN KEY (`id_km`) REFERENCES `KhuyenMai` (`id_km`);

ALTER TABLE `CT_HDN` ADD FOREIGN KEY (`id_sp`) REFERENCES `DanhMuc` (`id_dm`);

ALTER TABLE `CT_KM` ADD FOREIGN KEY (`id_dm`) REFERENCES `DanhMuc` (`id_dm`);

ALTER TABLE `CT_KM` ADD FOREIGN KEY (`id_sp`) REFERENCES `SanPham` (`id_sp`);

ALTER TABLE `SanPham` ADD FOREIGN KEY (`id_xx`) REFERENCES `SanPham` (`id_ncc`);

ALTER TABLE `TinTuc` ADD FOREIGN KEY (`id_ltt`) REFERENCES `LoaiTinTuc` (`id_ltt`);
