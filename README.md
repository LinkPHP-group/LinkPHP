# LinkPHP-v1.0
LinkPHP 是一款免费、开源基于 MVC 的 OPP 轻量级PHP开发框架 ，专为简化WEB、API而生。


框架官网地址：<a href='http://linkphp.org' target='_blank'>http://linkphp.org</a>

开发手册地址：http://www.kancloud.cn/linkphp_org/v1_0_0/287204



简单、易用

开发者不用关心框架内核的实现原理，只需关心项目所需的控制器、模型、中间件以及模版的实现即可。对于不熟悉smarty的开发者也可以在配置中关闭smarty，用自己最熟悉的网页嵌套php方式实现模版的开发



高效、安全

LinkPHP内核采用单一入口、按需加载以及垃圾回收机制，从而保证项目高效运行；三道盾牌为您的项目安全保驾护航(请求严格过滤、令牌校验、自定义中间件）



多库支持

LinkPHP框架友好支持项目中同时操作多个数据库，主要兼容Mysql、MsSQL、PgSQL、Sqlite、Oracle等主流数据库



WEB、API灵活切换

开发者只需在入口文件进行简单配置，便可实现WEB、API灵活切换。在WEB模式下，当出现错误、提示、404以及系统异常时均为网页提示，而处在API模式下时均以json的数据格式提示。



易于扩展、个性灵活

LinkPHP框架注重项目扩展和个性化定制，开发者只需将扩展的源码放到框架同级的ext目录下即可，同时开发者也可自定义“404、错误、成功”默认提示页面效果
