-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-03-06 07:00:48
-- 服务器版本： 5.7.17
-- PHP Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

--
-- 转存表中的数据 `cms_category`
--

INSERT INTO `cms_category` (`cid`, `pid`, `title`, `sort`) VALUES
(1, 0, '首页', 1),
(2, 0, '部门概况', 2),
(3, 0, '通知公告', 3),
(4, 0, '工作动态', 4),
(5, 4, '财务审计', 1),
(6, 4, '工程审计', 2),
(7, 4, '学习交流', 3),
(8, 0, '政策法规', 5),
(9, 0, '资料下载', 6),
(10, 9, '上级文件', 1),
(11, 9, '校内制度', 2),
(12, 9, '校内文件', 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
