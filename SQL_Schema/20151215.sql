-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- 主機: localhost
-- 建立日期: 2015 年 12 月 15 日 04:55
-- 伺服器版本: 5.5.45-cll-lve
-- PHP 版本: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `2015softwareengineering`
--

-- --------------------------------------------------------

--
-- 資料表結構 `privilege_ref`
--

CREATE TABLE IF NOT EXISTS `privilege_ref` (
  `privilege_id` int(11) NOT NULL,
  `type` text NOT NULL,
  `project_p` text NOT NULL,
  UNIQUE KEY `privilege_id` (`privilege_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` text NOT NULL,
  `p_des` text NOT NULL,
  `p_company` text NOT NULL,
  `p_owner` int(11) NOT NULL,
  `p_start_time` datetime NOT NULL,
  `p_end_time` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 資料表結構 `project_team`
--

CREATE TABLE IF NOT EXISTS `project_team` (
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  UNIQUE KEY `project_id_3` (`project_id`,`user_id`),
  KEY `project_id` (`project_id`,`user_id`),
  KEY `project_id_2` (`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 資料表結構 `req`
--

CREATE TABLE IF NOT EXISTS `req` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `rproject` int(11) NOT NULL,
  `rname` text NOT NULL,
  `rtype` int(11) NOT NULL,
  `rdes` text NOT NULL,
  `rowner` int(11) NOT NULL,
  `rpriority` int(11) NOT NULL,
  `rstatus` int(11) NOT NULL,
  `version` text NOT NULL,
  `oldVersion` int(11) DEFAULT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 資料表結構 `req_memo`
--

CREATE TABLE IF NOT EXISTS `req_memo` (
  `rm_id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `datetime` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`rm_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 資料表結構 `req_relation`
--

CREATE TABLE IF NOT EXISTS `req_relation` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `rid_a` int(11) NOT NULL,
  `rid_b` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 資料表結構 `req_review`
--

CREATE TABLE IF NOT EXISTS `req_review` (
  `reqreviewID` int(11) NOT NULL AUTO_INCREMENT,
  `reviewerID` int(11) NOT NULL,
  `req_ID` int(11) NOT NULL,
  `reviewComment` text NOT NULL,
  `decision` int(11) NOT NULL,
  PRIMARY KEY (`reqreviewID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 資料表結構 `testcase`
--

CREATE TABLE IF NOT EXISTS `testcase` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `t_des` text NOT NULL,
  `data` text NOT NULL,
  `pid` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 資料表結構 `test_relation`
--

CREATE TABLE IF NOT EXISTS `test_relation` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `confirmed` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`relation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 資料表結構 `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `account_id` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `company` text NOT NULL,
  `previlege` int(11) NOT NULL,
  `user_session` text,
  PRIMARY KEY (`uid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
