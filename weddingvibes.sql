-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 20 May 2020, 12:54:26
-- Sunucu sürümü: 10.4.11-MariaDB
-- PHP Sürümü: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `weddingvibes`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `activateuser`
--

CREATE TABLE `activateuser` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `secretkey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `activateuser`
--

INSERT INTO `activateuser` (`id`, `email`, `secretkey`) VALUES
(17, 'oguzkaandurak@gmail.com', '1589967858650020220'),
(18, 'test@vendor.com', '15899682481809043258');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adminusers`
--

CREATE TABLE `adminusers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `adminusers`
--

INSERT INTO `adminusers` (`id`, `email`, `pass`) VALUES
(1, 'admin@weddingvibes.com', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Mekanlar'),
(2, 'Organizasyon'),
(3, 'Fotoğrafçı'),
(4, 'Kuaför'),
(5, 'Çiçekçi'),
(6, 'Matbaa'),
(7, 'Giyim');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `serviceProviderId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `comment` varchar(1500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `serviceProviderId`, `userId`, `comment`) VALUES
(10, 12, 19, 'muvaqzzam'),
(11, 12, 19, 'muazzam'),
(12, 12, 32, 'çok çok güzel'),
(13, 12, 19, 'test');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `passreset`
--

CREATE TABLE `passreset` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `secretpasskey` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `servicefav`
--

CREATE TABLE `servicefav` (
  `id` int(11) NOT NULL,
  `serviceProviderId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `servicefav`
--

INSERT INTO `servicefav` (`id`, `serviceProviderId`, `userId`) VALUES
(56, 12, 32),
(57, 12, 19);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `serviceproviders`
--

CREATE TABLE `serviceproviders` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `vendor_name` text NOT NULL,
  `user_id` int(255) NOT NULL,
  `is_activated` int(255) NOT NULL COMMENT '1= true, 0= false',
  `latlng` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `serviceproviders`
--

INSERT INTO `serviceproviders` (`id`, `category_id`, `description`, `vendor_name`, `user_id`, `is_activated`, `latlng`) VALUES
(12, 2, 'Ankara organizasyon şirketlerinden Bubbles Unique Wishes, Çankaya’da yer alıyor. Düğün.com çiftlerine özel indirimler de sunan firmada fiyatlar kişi başı minimum 10-20 TL’den başlıyor.', 'Bubbles Unique Wishes', 20, 1, '39.96877813233304,32.79985223057124'),
(13, 6, 'DAVETİYE', 'DAVETİYE', 21, 1, '39.87317917859991,32.753948896279816'),
(14, 6, '1998 de İstanbul Fındıkzade de bir pasajın üst katlarında ajans olarak faaliyetlerimize başladık. Gelişen müşteri hacmimiz ile birlikte 2000 li yılların başlarında bir kaç eski matbaa makinesi ile matbaacılar sitesinde küçük bir atölye ile üretime başladık. Basit matbaa faaliyetlerinin yapıldığı küçük bir işletme olarak devam eden faaliyetlerimiz. Sürekli üretime ve teknolojiye yatırım yapan çalışma anlayışımız ile bu gün organize matbaa noktasına getirmiştir bizi.', 'DAVETİYE', 22, 1, '52.04824879765177,-4.157444035156259'),
(15, 3, 'Ankara düğün fotoğrafçısı tavsiyelerine Selman Dinç Photography ile başlayalım! Firma, uzun süredir fotoğrafçılık sektöründe hizmet veriyor.\r\nDüğün hikayesinden save the date\'e kadar ne isterseniz çekiyorlar. Çekim yaptıkları bölgeler genelde şöyle; Ankara ve tüm ilçeleri, Nevşehir Kapadokya ve Peri Bacaları, Kayseri Yılki Atları, Isparta Lavanta Bahçeleri, Burdur Salda Gölü.\r\nAnkara fotoğrafçılarından Selman Dinç Photography’nin minimum paket fiyatları ise 1.000-1.500 TL arasında değişiyor.\r\n', 'Düğün Fotoğrafı', 23, 1, '52.04824879765177,-4.157444035156259'),
(16, 3, 'Yaklaşık 10 yıldır fotoğrafçılık alanında hizmet veren Mert Küçük Photographer, Çankaya bölgesinde yer alıyor. Ancak dilerseniz şehir dışı ve yurt dışı çekimlerine de gelebiliyor.\r\nFirma sayfasında çektikleri birbirinden güzel düğün fotoğrafları var. Çiftler için detay çalışmalarından drone ile manzara çekimlerine kadar hemen her çalışmayı yapıyorlar. Düğün fotoğraflarına verilen hafif bohem efektleri de çok sevdiğimi belirtmeden geçmeyeyim.\r\nDüğün fotoğrafı çekimi için fiyatları 2.000-3.000 TL’den başlıyor.\r\n', 'Düğün Fotoğrafı', 24, 1, '52.09546818469914,-3.431742318356179'),
(17, 5, 'Gelin el çiçeği, araç süsleme, organizasyon çiçekleri gibi hizmet almak istediğiniz alanda; taze veya yapma çiçek çeşitleri, mevsime ve mekana uygun çiçek modelleri ile hizmetinizdeyiz. Nilüfer çiçekçilik olarak sizlerin en özel günlerini küçük detaylar ile süslemek bizim işimiz. iletişime geçmek için teklif almanız yeterli.', 'Çiçek', 25, 1, '52.37806126137426,-6.804660775497968'),
(18, 7, 'Gelinlik sektöründe 3. kuşak olarak küçük yaşlardan beri tutkunu olduğum bir mesleğin içindeyim. Ben ve ekibim işimizi severek yapıyoruz.\r\n​\r\nHaute couture cözümlerle prova asamasından özel gününüze kadar maksimum ilgi ve özen göstermekteyiz. Sizi siz kadar düsünen ve güzel eserler yaratmak icin cabalayan ekibimiz son ana kadar titizlikle calısıyor. Kısacası biz, size yakısan ve bizi gururlandıran kıyafetler hazırlıyoruz.\r\n \r\nO en özel ve en güzel gününüzde hersey hayal ettiginiz gibi olsun.\r\n \r\nMerve (Şengün) Karaman\r\nDesigner\r\n', 'Tasarım ', 26, 1, '52.00092780179908,-4.201389347656259'),
(19, 7, 'Bütün detayları sizin belirleyeceğiniz ve kendinizi özgür hissedeceğiniz bir modaevinde düşlediğiniz gelinliği hep birlikte tasarlamaya ne dersiniz? Kaliteden hiçbir şekilde ödün vermeden faaliyet gösteren, kumaşın dokusu, modellerin çeşitliliği, iplik ve aksesuarların orjinalliği ile Ankara’daki birçok modaevinden ayrılan Mehtap Modaevi, tercih ettiği gelinlerin mutluluğunu ve memnuniyetini sağlamayı ilke ediniyor. Her hizmet ve işlemde isteklerini ön planda tutan, hayallerini gerçekleştirmek için tüm ihtiyaç ve beklentilerini tam olarak karşılayan Mehtap Modaevi ile kusursuz bir gelin olacağınızdan emin olabilirsiniz.\r\n', 'MEHTAP GELİNLİK', 27, 1, '51.899357010604156,-4.234348332031259'),
(20, 7, '2020 İlkbahar/Yaz sezonunda gelinlikler, klasiklerin yeni yorumları olarak karşımıza çıkarken; geçmiş zamanlardan miras silüetler zamanın ötesinde bir tavır ortaya koyuyor.\r\n\r\nRomantik kesimler, modern aksesuarlar ve yüksek doz ışıltıya sahip göz alıcı detayları bir araya getiren Vakko Wedding koleksiyonu, yeni sezonda büyüleyici bir masalı gerçeğe dönüştürüyor.\r\n', 'Vakko Gelinlik', 28, 1, '52.39821049396907,-6.969944035156259'),
(21, 1, 'Genel Özellikler\r\n\r\nKategori\r\nDüğün Salonları\r\n\r\nYemeksiz Paket Başlangıç Fiyatı \r\n5.000-7.500 TL\r\n\r\nKapasite\r\n750-1.000\r\n\r\nİlçe, İl\r\nSincan, Ankara\r\n\r\nMinimum Yemekli Kişi Başı Fiyat\r\n30-50 TL\r\n\r\nHakkımızda\r\nDoruk Park Balo Nikah Salonu adı ile düğün ve davet sektöründe büyük başarılara imza atıyoruz. Yenilikçi bir bakış açısı ve her yaştan çiftin beğenisine hitap edebilen bir hizmet anlayışı ile düğünler düzenliyoruz. Düğünler haricinde ise; kına geceleri, nikâh törenleri, sünnetler, nişan merasimleri, kurumsal etkinlikler gibi önemli gün ve gecelere de aynı özenle ev sahipliği yapabiliyoruz. O halde bu organizasyonları nerede düzenliyor ve ne tarz imkânlar sunuyoruz biraz bahsedelim. Düğün mekânınızı bulmanızda daha rahat bir seçim yapabilmek ve doğru kararı verdiğinizden emin olabilmek için bu bilgileri okumanızda fayda görüyoruz.\r\n\r\nÖncelikle bizimle görüşen ve düğünü için bilgi almak isteyen çiftlerimize 2 ayrı salonumuz olduğundan bahsediyoruz. Her ikisi de özenle düzenlenip modern bir şekilde dekore edilen salonlarımız günümüzün estetik anlayışına ve çiftlerimizin beğenisine uygundur. Büyüklük ve dizayn açısından farklılık gösteren salonlarımızda göreceğiniz ilgi alâka ve alacağınız hizmet içerikleri kesinlikle aynıdır', 'Doruk Park', 29, 1, '52.12925405592645,-3.871799503906259'),
(23, 1, 'test', 'test', 20, 1, '39.87269349858999,32.81329478934571');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `spdates`
--

CREATE TABLE `spdates` (
  `id` int(11) NOT NULL,
  `sp_id` int(255) NOT NULL,
  `dateData` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `spdates`
--

INSERT INTO `spdates` (`id`, `sp_id`, `dateData`) VALUES
(39, 12, '23-05-2020'),
(40, 12, ' 28-05-2020'),
(46, 13, '20-05-2020'),
(47, 13, ' 21-05-2020'),
(48, 13, ' 22-05-2020'),
(49, 13, ' 23-05-2020'),
(50, 13, ' 24-05-2020'),
(51, 12, '27-05-2020'),
(52, 23, '20-05-2020'),
(53, 23, ' 21-05-2020'),
(54, 23, ' 22-05-2020'),
(55, 23, ' 23-05-2020'),
(56, 23, ' 24-05-2020');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `spimages`
--

CREATE TABLE `spimages` (
  `id` int(11) NOT NULL,
  `sp_id` int(11) NOT NULL,
  `photoUrl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `spimages`
--

INSERT INTO `spimages` (`id`, `sp_id`, `photoUrl`) VALUES
(24, 11, 'img_data/202005200719441781584304.jpg'),
(25, 11, 'img_data/202005200719451167369436.jpg'),
(26, 11, 'img_data/202005200719452133215987.jpg'),
(30, 12, 'img_data/20200520072448435786863.jpg'),
(31, 12, 'img_data/20200520072448716701173.jpg'),
(32, 13, 'img_data/20200520103417402215656.jpg'),
(33, 13, 'img_data/20200520103417180520876.jpg'),
(34, 13, 'img_data/202005201034171765798765.jpg'),
(35, 13, 'img_data/202005201034171619242509.jpg'),
(36, 14, 'img_data/202005201035041741524199.jpg'),
(37, 14, 'img_data/202005201035041429368040.jpg'),
(38, 14, 'img_data/202005201035052044690248.jpg'),
(39, 14, 'img_data/202005201035051087756628.jpg'),
(40, 14, 'img_data/20200520103505907974245.jpg'),
(41, 15, 'img_data/20200520103702807252941.jpg'),
(42, 15, 'img_data/202005201037021053495108.jpg'),
(43, 15, 'img_data/2020052010370260153264.jpg'),
(44, 15, 'img_data/202005201037021397633980.jpg'),
(45, 15, 'img_data/202005201037021324175181.jpg'),
(46, 15, 'img_data/202005201037022106008856.jpg'),
(47, 16, 'img_data/2020052010382467234055.jpg'),
(48, 16, 'img_data/20200520103824548153207.jpg'),
(49, 16, 'img_data/20200520103824847113341.jpg'),
(50, 16, 'img_data/20200520103824792621379.jpg'),
(51, 16, 'img_data/202005201038241852528153.jpg'),
(52, 16, 'img_data/20200520103824969976049.jpg'),
(53, 16, 'img_data/20200520103824393675899.jpg'),
(54, 16, 'img_data/2020052010382569136813.jpg'),
(55, 17, 'img_data/20200520103941999307765.jpg'),
(56, 17, 'img_data/202005201039411579693809.jpg'),
(57, 18, 'img_data/202005201040391297354512.jpg'),
(58, 18, 'img_data/20200520104039782366984.jpg'),
(59, 18, 'img_data/202005201040391966582169.jpg'),
(60, 18, 'img_data/202005201040391612063211.jpg'),
(61, 18, 'img_data/202005201040391238884248.jpg'),
(62, 18, 'img_data/20200520104039572488485.jpg'),
(63, 18, 'img_data/202005201040391658978474.jpg'),
(64, 19, 'img_data/202005201041361785371140.jpg'),
(65, 19, 'img_data/20200520104136483984017.jpg'),
(66, 19, 'img_data/20200520104137368942774.jpg'),
(67, 19, 'img_data/20200520104137104552109.jpg'),
(68, 19, 'img_data/20200520104137476809982.jpg'),
(69, 20, 'img_data/20200520104231232726592.jpg'),
(70, 20, 'img_data/202005201042311204734032.jpg'),
(71, 20, 'img_data/20200520104231217551610.jpg'),
(72, 20, 'img_data/202005201042311163016952.jpg'),
(73, 20, 'img_data/202005201042311105904678.jpg'),
(74, 20, 'img_data/202005201042311565281182.jpg'),
(75, 20, 'img_data/2020052010423151503449.jpg'),
(76, 20, 'img_data/202005201042311424036348.jpg'),
(77, 21, 'img_data/20200520104344699664095.jpg'),
(78, 21, 'img_data/20200520104344849151814.jpg'),
(79, 21, 'img_data/202005201043441208422506.jpg'),
(80, 21, 'img_data/202005201043441208516437.jpg'),
(81, 21, 'img_data/202005201043442065432705.jpg'),
(82, 21, 'img_data/20200520104344786061427.jpg'),
(83, 22, 'img_data/20200520104436470415313.jpg'),
(84, 22, 'img_data/202005201044367922446.jpg'),
(85, 22, 'img_data/20200520104436640221948.jpg'),
(86, 22, 'img_data/202005201044361121972711.jpg'),
(87, 22, 'img_data/20200520104436577883617.jpg'),
(88, 23, 'img_data/202005201153311603246075.jpg'),
(89, 23, 'img_data/202005201153311152612764.jpg'),
(90, 23, 'img_data/202005201153322042766117.jpg'),
(91, 23, 'img_data/202005201153321821542756.jpg'),
(92, 23, 'img_data/2020052011533295686380.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `userappointments`
--

CREATE TABLE `userappointments` (
  `id` int(11) NOT NULL,
  `dateData` varchar(255) NOT NULL,
  `sp_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_active` int(11) NOT NULL COMMENT '0=inactive,\r\n1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `userappointments`
--

INSERT INTO `userappointments` (`id`, `dateData`, `sp_id`, `user_id`, `is_active`) VALUES
(3, '23-05-2020', 12, 19, 1),
(4, '28-05-2020', 12, 32, 1),
(5, '27-05-2020', 12, 19, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name_surname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL COMMENT '0 = admin, 1=user, 2=vendor',
  `firebaseUID` varchar(255) NOT NULL,
  `photoUrl` varchar(255) NOT NULL,
  `is_active` int(11) NOT NULL COMMENT '1 = yes, 0 = no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name_surname`, `address`, `email`, `password`, `phone`, `user_type`, `firebaseUID`, `photoUrl`, `is_active`) VALUES
(19, 'Oğuz Kaan Durak', '', 'oguzkaandurak2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '5546081855', '1', 'c1uiRpSpBxRRwTOr0IQ8Jz6m9G23', '', 1),
(20, 'Bubbles Unique Wishes', 'ankara/çankaya', 'bubble@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '5546081855', '2', 'qRY1bFMHqpeW1N5lC783qESM7Ah1', '', 1),
(21, 'Liza Davetiye', 'ankara', 'liza@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'BruDsU1YIidHTTwoPeoUGSHJOjx2', '', 1),
(22, 'MİRA Davetiye', 'ankara', 'mira@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'SLr1FTNS9gcE1CFns1RdPRkPCQ42', '', 1),
(23, 'Selman Dinç Photography', 'ankara', 'selman@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'XeW5exDFLlP0yafabYXY3ivyAYj1', '', 1),
(24, 'Mert Küçük Photographer', 'ankara', 'mert@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'ziAS1948y4OP3TiKllAArA8v8Ii1', '', 1),
(25, 'Nilüfer Çiçekçilik', 'ankara', 'nilufer@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'QRYJdBwvLGVxSt4TZeDSDHlYsCZ2', '', 1),
(26, 'Merve Karaman Bridal', 'ankara', 'merve@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'i66TapMpdbg38PD0BgIi5MUSh2n1', '', 1),
(27, 'MEHTAP GELİNLİK', 'ankara', 'mehtap@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'N2mBMMCwPpMNVmX25POHH8W27M52', '', 1),
(28, 'VAKKO BRIDE', 'ankara', 'vakko@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'bFB66K8cvdW4yR2cMZCsVkZhhk52', '', 1),
(29, 'DORUK PARK', 'ankara', 'doruk@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'Vazw7eUtmYN5xFm9lb3fzm5goqH2', '', 1),
(30, 'Park Lamore Garden', 'ankara', 'park@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'qUje8KhAC6fapMwvaKmW71Wbx8D3', '', 1),
(31, 'Handem Organizasyon', 'ankara', 'handem@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'lJHd9ABdxCSoTAHcOPwjF6FbEKF2', '', 1),
(32, 'kaan durak', '', 'kaandurak92@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '1', '01hORspdUEXv8u2455Ks6QEdLfo2', '', 1),
(33, 'kaan durak', '', 'oguzkaandurak@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '1', 'zwkkvtlqYQXCrZgZbwIx2GVdvFq1', '', 0),
(34, 'vendortest', 'ankara', 'test@vendor.com', 'e10adc3949ba59abbe56e057f20f883e', '+905546081855', '2', 'kNPPaU8edseWjNRjPU1ym1LLsvY2', '', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `activateuser`
--
ALTER TABLE `activateuser`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `adminusers`
--
ALTER TABLE `adminusers`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `passreset`
--
ALTER TABLE `passreset`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `servicefav`
--
ALTER TABLE `servicefav`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `serviceproviders`
--
ALTER TABLE `serviceproviders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `spdates`
--
ALTER TABLE `spdates`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `spimages`
--
ALTER TABLE `spimages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `userappointments`
--
ALTER TABLE `userappointments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `activateuser`
--
ALTER TABLE `activateuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `adminusers`
--
ALTER TABLE `adminusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `passreset`
--
ALTER TABLE `passreset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `servicefav`
--
ALTER TABLE `servicefav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Tablo için AUTO_INCREMENT değeri `serviceproviders`
--
ALTER TABLE `serviceproviders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Tablo için AUTO_INCREMENT değeri `spdates`
--
ALTER TABLE `spdates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- Tablo için AUTO_INCREMENT değeri `spimages`
--
ALTER TABLE `spimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- Tablo için AUTO_INCREMENT değeri `userappointments`
--
ALTER TABLE `userappointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
