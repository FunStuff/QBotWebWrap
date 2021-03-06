QQ机器人WEB控制台
=========  
[![Build Status](https://travis-ci.org/zeruniverse/QBotWebWrap.svg)](https://travis-ci.org/zeruniverse/QBotWebWrap) ![Environment](https://img.shields.io/badge/PHP-5.2+-blue.svg)
![License](https://img.shields.io/github/license/zeruniverse/QBotWebWrap.svg)      
***该项目为QQRobot, QQParking, QzoneLiker提供一个网页版控制台，降低了使用门槛。***
有关[QQRobot](https://github.com/zeruniverse/QQRobot), [QQParking](https://github.com/zeruniverse/QQParking), [QzoneLiker](https://github.com/zeruniverse/QzoneLiker) 请见原项目。
用户输入基本参数后点击运行，然后等待网页中出现二维码用手机QQ（或安全中心）扫描登陆即可。  
  
#QQBOT WEB WRAP
This project is a Web-based console for [QQRobot](https://github.com/zeruniverse/QQRobot), [QQParking](https://github.com/zeruniverse/QQParking) and [QzoneLiker](https://github.com/zeruniverse/QzoneLiker).  
Users no longer need to type command in Terminal. They can now do it with a web browser!  
  
##如何使用（服务器端配置）  
+ 确保安装有PHP 5.2+，MySQL与python2.7  
+ 新建一个文件夹并将此作为网站根目录，将[QBotWebWrap](https://github.com/zeruniverse/QBotWebWrap/releases/download/1.3/QBotWebWrap.zip)解压至文件夹内。   
+ 新建一个数据库，导入[qwrap.sql](https://github.com/zeruniverse/QBotWebWrap/releases/download/1.3/qwrap.sql)。    
+ 假设您刚刚建立的文件夹是qwrapweb. ```cd qwrapweb```   
+ 将里面的qqbot文件夹权限改为777 ```sudo chmod -R 777 qqbot```    
+ 将function/sqllink.php改为您自己的数据库用户名，数据库和密码。 ```vim function/sqllink.php```  
  
您可以改变qqbot文件夹内各项目的qqbot.py将tulingkey, SMTP邮箱等参数改为自己的参数。避免公用资源被耗尽引起错误。具体方案请参考3个原项目。  

##如何使用（客户端）

+ 打开浏览器，在主页选择需要启动的项目  
+ 输入参数（如有必要），点击 RUN XXX   
+ 浏览器稍候将自动跳转至状态页  
+ 等待状态页QR CODE 出现  
+ 用手机QQ/安全中心扫描QR CODE  
+ 等待QR CODE消失  
+ 记录状态页地址并关闭页面  
  
如需检查LOG，打开您记录下的状态地址即可。  