响应状态码
- 0 成功
- 1 空数据
- 10001 校验错误
- 10002 未知错误；提示用户网络问题，稍后再试
- 20001 短信失败
- 20002 支付失败
- 其他 后端自定义

短信
- 供应方
- 调用keys

支付方式
- 支付宝
- 微信

视频存储
直播

签到规则
- 1~7 天每天递增 X 积分，第一天 Y 积分

退款原因
- 不喜欢/不想要
- 货物一直未送到
- 货物破损已拒签
- 未按约定时间发货

学校
* 省，市，县
* 名称
* 地址
* 学校类型
* 入学途径
* 班级类型
* 特殊招生
* *住宿类型*
* *联系方式*

```
# white board response error
array:2 [
  "code" => 200
  "msg" => array:4 [
    "room" => array:15 [
      "id" => 137637
      "name" => "vi-rom"
      "limit" => 0
      "teamId" => 431
      "adminId" => 306
      "mode" => "persistent"
      "template" => "meeting"
      "region" => "cn"
      "uuid" => "47db1bfc56604bf6815f01788daf094c"
      "updatedAt" => "2019-07-12T01:54:15.513Z"
      "createdAt" => "2019-07-12T01:54:15.397Z"
      "appIdentifier" => "com.herewhite.whiteboard"
      "appHash" => "004ab57536b28e23c4268721e74dfa16"
      "appVersion" => "2.0.0"
      "akkoVersion" => "1.4.2"
    ]
    "hare" => array:13 [
      "uuid" => "47db1bfc56604bf6815f01788daf094c"
      "appIdentifier" => "com.herewhite.whiteboard"
      "appVersion" => "2.0.0"
      "appHash" => "004ab57536b28e23c4268721e74dfa16"
      "akkoVersion" => "1.4.2"
      "teamId" => "431"
      "mode" => "persistent"
      "region" => "cn"
      "isBan" => false
      "createdAt" => 1562896455481
      "updatedAt" => 1562896455484
      "usersMaxCount" => 0
      "survivalDuration" => 30000
    ]
    "roomToken" => "WHITEcGFydG5lcl9pZD1PRkJ3aDgxYzZZZ01xVm05TVJQSWZqTk1Ya3liclgySkZoeE0mc2lnPWQ2YWZlNjc0NDFmZDUyMTQ2MGExN2M5NzQwYmYyZTBhNjE5NDAyNDM6YWRtaW5JZD0zMDYmcm9vbUlkPTQ3ZGIxYmZjNTY2MDRiZjY4MTVmMDE3ODhkYWYwOTRjJnRlYW1JZD00MzEmcm9sZT1yb29tJmV4cGlyZV90aW1lPTE1OTQ0NTM0MDcmYWs9T0ZCd2g4MWM2WWdNcVZtOU1SUElmak5NWGt5YnJYMkpGaHhNJmNyZWF0ZV90aW1lPTE1NjI4OTY0NTUmbm9uY2U9MTU2Mjg5NjQ1NTQxODAw"
    "code" => 201
  ]
]
```