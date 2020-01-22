# HackShanghai

[HackShanghai官方网站](https://hackshanghai.com/)

## 推荐运行环境

- CentOS 7 + Apache 2.4.41 + PHP 7.2.26 + MySQL 5.6.45

- 修改`php.ini`如下：

  ```ini
  post_max_size = 8M
  upload_max_filesize = 8M
  ```

## 部署数据库

运行以下MySQL命令：

```mysql
CREATE DATABASE `hacksh` CHARACTER SET 'utf8' COLLATE 'utf8_bin';
CREATE TABLE `hacksh`.`form`
(
    `form_id`              INT(0)       NOT NULL AUTO_INCREMENT,
    `update_time`          timestamp    NULL DEFAULT CURRENT_TIMESTAMP,
    `name`                 VARCHAR(40)  NOT NULL,
    `birthday`             date         NOT NULL,
    `country`              VARCHAR(40)  NOT NULL,
    `province`             VARCHAR(40)  NOT NULL,
    `city`                 VARCHAR(40)  NOT NULL,
    `district`             VARCHAR(40)  NOT NULL,
    `school`               VARCHAR(40)  NOT NULL,
    `major`                VARCHAR(40)  NOT NULL,
    `grade`                VARCHAR(40)  NOT NULL,
    `id`                   VARCHAR(40)  NOT NULL,
    `phone`                VARCHAR(40)  NOT NULL,
    `im`                   VARCHAR(40)  NOT NULL,
    `github`               VARCHAR(40)  NOT NULL,
    `talent`               VARCHAR(40)  NOT NULL,
    `experience`           VARCHAR(200) NOT NULL,
    `has_team`             boolean      NOT NULL,
    `leader_name`          VARCHAR(40)  NOT NULL,
    `cv_original_filename` VARCHAR(40)  NOT NULL,
    `cv_stored_filename`   VARCHAR(40)  NOT NULL,
    PRIMARY KEY (`form_id`)
) ENGINE = InnoDB;
```

## 部署配置文件

创建配置文件`.htconfig.php`，内容如下：

```php
<?php
return array(
    'DB_HOST' => 'localhost', // 数据库地址
    'DB_USER' => 'root', // 数据库用户名
    'DB_PWD' => '', // 数据库密码
    'DB_NAME' => 'hacksh', // 数据库库名
    'OP_PWD' => '69c5fcebaa65b560eaf06c3fbeb481ae44b8d618' // 数据导出页面密码=sha1(sha1($pass))
);
```

## 设置访问权限

在`upload`文件夹中创建`.htaccess`文件，内容如下：

```
Order allow,deny
Deny from all
```

这可以阻止web用户访问或执行该文件夹中的内容。

配置文件文件名以`.ht`开头，故apache默认设置即可拒绝web用户访问。