-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Хост: 10.0.0.211:3306
-- Время создания: Июн 06 2023 г., 18:43
-- Версия сервера: 10.3.25-MariaDB-log
-- Версия PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `j0366495_metubee`
--

-- --------------------------------------------------------

--
-- Структура таблицы `channel_playlist`
--

CREATE TABLE `channel_playlist` (
  `chID` int(11) NOT NULL,
  `chPlaylist` longtext CHARACTER SET utf8mb4 NOT NULL,
  `chTo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `commentID` int(11) NOT NULL,
  `commentBy` int(11) NOT NULL,
  `commentContent` varchar(144) CHARACTER SET utf8mb4 NOT NULL,
  `commentVideo` int(11) NOT NULL,
  `commentAdded` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `community`
--

CREATE TABLE `community` (
  `communityID` int(11) NOT NULL,
  `communityBy` int(11) NOT NULL,
  `communityTo` int(11) NOT NULL,
  `communityContent` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dislikes`
--

CREATE TABLE `dislikes` (
  `dislikeID` int(11) NOT NULL,
  `dislikeFrom` int(11) NOT NULL,
  `dislikeVid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dislikes_comments`
--

CREATE TABLE `dislikes_comments` (
  `dislikeID` int(11) NOT NULL,
  `dislikeFrom` int(11) NOT NULL,
  `dislikeTo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `favoriteID` int(11) NOT NULL,
  `favoriteTo` int(11) NOT NULL,
  `favoriteVideoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `fav_channels`
--

CREATE TABLE `fav_channels` (
  `favID` int(11) NOT NULL,
  `favTo` int(11) NOT NULL,
  `favCh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `flags`
--

CREATE TABLE `flags` (
  `flagID` int(11) NOT NULL,
  `flagTo` int(11) NOT NULL,
  `flagFrom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE `history` (
  `historyID` int(11) NOT NULL,
  `historyTo` int(11) NOT NULL,
  `historyVideoID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `likeID` int(11) NOT NULL,
  `likeFrom` int(11) NOT NULL,
  `likeVid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `likes_comments`
--

CREATE TABLE `likes_comments` (
  `likeID` int(11) NOT NULL,
  `likeFrom` int(11) NOT NULL,
  `likeTo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `links`
--

CREATE TABLE `links` (
  `linkID` int(11) NOT NULL,
  `linkTo` int(11) NOT NULL,
  `linkTitle` varchar(24) CHARACTER SET utf8mb4 NOT NULL,
  `linkURL` longtext CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `notificationTo` int(11) NOT NULL,
  `notificationBy` int(11) NOT NULL,
  `notificationAction` int(11) NOT NULL,
  `notificationActionURL` varchar(255) NOT NULL,
  `notificationAdded` varchar(255) NOT NULL,
  `notificationNew` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `playlists`
--

CREATE TABLE `playlists` (
  `playlistID` int(11) NOT NULL,
  `playlistTitle` varchar(72) CHARACTER SET utf8mb4 NOT NULL,
  `playlistDescription` longtext CHARACTER SET utf8mb4 NOT NULL,
  `playlistBy` int(11) NOT NULL,
  `playlistImage` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `playlistGetID` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `playlist_videos`
--

CREATE TABLE `playlist_videos` (
  `pvID` int(11) NOT NULL,
  `pvVideo` int(11) NOT NULL,
  `pvTo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscribeID` int(11) NOT NULL,
  `subscribeTo` int(11) NOT NULL,
  `subscribeBy` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `username` varchar(72) CHARACTER SET utf8mb4 NOT NULL,
  `password` longtext CHARACTER SET utf8mb4 NOT NULL,
  `email` longtext CHARACTER SET utf8mb4 NOT NULL,
  `bio` longtext CHARACTER SET utf8mb4 NOT NULL,
  `joined` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `profileImage` longtext CHARACTER SET utf8mb4 NOT NULL,
  `profileBanner` longtext CHARACTER SET utf8mb4 NOT NULL,
  `views` int(11) NOT NULL,
  `verifed` int(11) NOT NULL,
  `trailer_video` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `admin` int(11) NOT NULL,
  `ban` varchar(255) CHARACTER SET utf8mb4 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `videos`
--

CREATE TABLE `videos` (
  `videoID` int(11) NOT NULL,
  `videoGetID` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `videoTitle` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `videoBy` int(11) NOT NULL,
  `videoViews` int(11) NOT NULL,
  `videoDuration` int(11) NOT NULL,
  `videoAdded` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `videoCategory` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `videoTags` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `videoDescription` longtext CHARACTER SET utf8mb4 NOT NULL,
  `videoPrivate` int(11) NOT NULL,
  `videoFeatured` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `watch_later`
--

CREATE TABLE `watch_later` (
  `wlID` int(11) NOT NULL,
  `wlVideo` int(11) NOT NULL,
  `wlTo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `channel_playlist`
--
ALTER TABLE `channel_playlist`
  ADD PRIMARY KEY (`chID`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`);

--
-- Индексы таблицы `community`
--
ALTER TABLE `community`
  ADD PRIMARY KEY (`communityID`);

--
-- Индексы таблицы `dislikes`
--
ALTER TABLE `dislikes`
  ADD PRIMARY KEY (`dislikeID`);

--
-- Индексы таблицы `dislikes_comments`
--
ALTER TABLE `dislikes_comments`
  ADD PRIMARY KEY (`dislikeID`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favoriteID`);

--
-- Индексы таблицы `fav_channels`
--
ALTER TABLE `fav_channels`
  ADD PRIMARY KEY (`favID`);

--
-- Индексы таблицы `flags`
--
ALTER TABLE `flags`
  ADD PRIMARY KEY (`flagID`);

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`historyID`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`likeID`);

--
-- Индексы таблицы `likes_comments`
--
ALTER TABLE `likes_comments`
  ADD PRIMARY KEY (`likeID`);

--
-- Индексы таблицы `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`linkID`);

--
-- Индексы таблицы `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`);

--
-- Индексы таблицы `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`playlistID`);

--
-- Индексы таблицы `playlist_videos`
--
ALTER TABLE `playlist_videos`
  ADD PRIMARY KEY (`pvID`);

--
-- Индексы таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscribeID`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- Индексы таблицы `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`videoID`);

--
-- Индексы таблицы `watch_later`
--
ALTER TABLE `watch_later`
  ADD PRIMARY KEY (`wlID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `channel_playlist`
--
ALTER TABLE `channel_playlist`
  MODIFY `chID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `community`
--
ALTER TABLE `community`
  MODIFY `communityID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dislikes`
--
ALTER TABLE `dislikes`
  MODIFY `dislikeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dislikes_comments`
--
ALTER TABLE `dislikes_comments`
  MODIFY `dislikeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favoriteID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `fav_channels`
--
ALTER TABLE `fav_channels`
  MODIFY `favID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `flags`
--
ALTER TABLE `flags`
  MODIFY `flagID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `historyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `likes_comments`
--
ALTER TABLE `likes_comments`
  MODIFY `likeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `links`
--
ALTER TABLE `links`
  MODIFY `linkID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `playlists`
--
ALTER TABLE `playlists`
  MODIFY `playlistID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `playlist_videos`
--
ALTER TABLE `playlist_videos`
  MODIFY `pvID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscribeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `videos`
--
ALTER TABLE `videos`
  MODIFY `videoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `watch_later`
--
ALTER TABLE `watch_later`
  MODIFY `wlID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
