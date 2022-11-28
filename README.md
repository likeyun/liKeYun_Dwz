# 项目停止维护
该项目停止维护，已被集成至我另一个项目，请移步至【引流宝】项目
https://github.com/likeyun/liKeYun_Huoma

# 项目停止维护
该项目停止维护，已被集成至我另一个项目，请移步至【引流宝】项目
https://github.com/likeyun/liKeYun_Huoma

# 项目停止维护
该项目停止维护，已被集成至我另一个项目，请移步至【引流宝】项目
https://github.com/likeyun/liKeYun_Huoma

# liKeYun_Dwz
这是一套开源、免费、自建的短链接生成程序，可以通过本套程序快速自建属于自己的短链接生成平台，有丰富的功能和便捷的API，可以帮助你进行各项推广任务！

# 微信扫码进群
交流、解决问题、定制、学习等可以加入我们的开发者交流群
https://t.focus-img.cn/sh740wsh/bbs/p2/5d81cbd190009054cd755445e3d4d7fe.png

# 更新日志
2022-04-23:新增自定义参数，优化UI

# 安装
只需要访问install目录即可进入安装流程，简单输入数据库信息、管理员信息即可快速安装，请在php5.6 - 7.4版本内安装。<a href="https://github.com/likeyun/liKeYun_Dwz/blob/main/%E5%AE%9D%E5%A1%94%E5%AE%89%E8%A3%85%E6%95%99%E7%A8%8B.md">宝塔面板-安装教程</a><br/>

如果你的是Nginx服务器，那么你还需要配置伪静态规则<br/><br/>
Nginx伪静态规则<br/>
```
location / {
  if (!-e $request_filename) {
    rewrite ^/(.*)$ /index.php?id=$1 last;
  }
}
```
<br/>
Apache伪静态请在短网址系统index.php的同一目录建一个文件名为.htaccess的伪静态文件<br/><br/>
Apache伪静态规则<br/><br/>

```
RewriteEngine On
#RewriteBase / 
RewriteRule ^(\w+)$ index.php?id=$1
```

# 快捷创建页面配置
为了方便他人使用或者自己的使用，我们提供了快捷创建页面，快捷创建页面我们设置了默认配置，如需自己配置，可以前往/index/chuangjian.php进行配置，配置项如下，具体的参数代表什么意思，请阅读开发文档。<br/>
```
$dwz_title = '快捷创建';
$dwz_reditype = '1';
$dwz_type = '1';
$dwz_keynum = '5';
$dwz_url = trim($_REQUEST["dwz_url"]);
$api_key = 'kuaijie';
$dwz_yxq = 'ever';
```

# 框架/语言
前端框架：Bootstrap+jQuery<br/>
后端框架：原生php+mySQL<br/>

# 使用
管理后台路径：/index

# 版本和功能
版本：2.0.1<br/>
功能：<br/>
（1）可选直跳和防封跳转<br/>
（2）设置入口域名和防封域名<br/>
（3）可设置设备限制（只能微信内打开、只能手机浏览器打开、只能电脑浏览器打开、只能Android设备打开、只能iOS设备打开）<br/>
（4）可设置短网址Key的随机数位数（4位随机数、5位随机数、6位随机数）<br/>
（5）可设置短网址有效期<br/>
（6）可以随时停用短网址<br/>
（7）支持API创建<br/>
（8）支持快捷创建<br/>
（9）支持Apache和Nginx服务器<br/>
（10）可查看总访问量、昨天访问量、今天访问量、本月访问量、API请求次数、短链接总数等数据<br/>

# 截图
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154215.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154227.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154259.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154314.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154322.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154333.png" /><br/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20211028154340.png" /><br/>

# 支持与赞赏
如果您喜欢我的作品，想要支持我，请微信扫码<br/><br/>
<img src="https://camo.githubusercontent.com/5fae9333ccce7aaf5dc8edd3bbbcf925a08c4d43d85a904e60073b167ef0043f/68747470733a2f2f702e7073746174702e636f6d2f6f726967696e2f7067632d696d6167652f6334663164366237353332343435646562643062656463383862623731643166" width="300"/>

# 安装支持与交流
交流、解决问题、定制、学习等可以加入我们的开发者交流群
https://sc01.alicdn.com/kf/H574da7b723cd4c088b082ab93ab6eb8dV.png<br/>
如需加入作者的交流群，请加微信：sansure2016 备注进群。<br/>
里客云开源工具交流1群（已满500人）<br/>
里客云开源工具交流2群（已满500人）<br/>
里客云开源工具交流3群（已有400多人）
