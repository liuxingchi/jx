<?php
namespace Ydzy\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	
    public function indexAction()
    {
        return $this->render('YdzyTestBundle:Default:index.html.twig');
    }


    public function menuAction()
    {
        return $this->render('YdzyTestBundle:Default:menu.html.twig');
    }
	
     public function followAction()
    {
    	$params = array(
				array(
					'name' => 'follow',
					'url' => 'api/follow',
					'params' => array(
								'followed_uid'
					)
				 ),
				 array(
					'name' => 'myfollow',
					'url' => 'api/myfollow',
					'params' => array(
								'start',
								'num'
					)
				 ),
				 array(
					'name' => 'cancelfollow',
					'url' => 'api/cancelfollow',
					'params' => array(
								'follow_id'
					)
				 ),
				 array(
					'name' => 'isfollow',
					'url' => 'api/isfollow',
					'params' => array(
								'uid'
					)
				 ),
				 array(
					'name' => 'follownews',
					'url' => 'api/follownews',
					'params' => array(
								'start',
								'num',
								'post'
								
					)
				 ),
				 array(
					'name' => '',
					'url' => 'api/onepushtml',
					'params' => array(
								'pid'
					)
				 )
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	public function calllistAction()
    {
    	$params = array(
				array(
					'name' => 'calllist',
					'url' => 'api/calllist',
					'params' => array(
								'name',
								'tel',
								'mark'
					)
				 ),
				 array(
					'name' => 'mycalllist',
					'url' => 'api/mycalllist',
					'params' => array(
								'start',
								'num'
					)
				 ),
				 array(
					'name' => 'delectcall',
					'url' => 'api/delectcall',
					'params' => array(
								'calllist_id'
					)
				 ),
				 array(
					'name' => 'delectall',
					'url' => 'api/delectall',
					'params' => array()
				 )
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
    public function categoryAction()
    {
    	$params = array(
				array(
					'name' => 'retireveall',
					'url' => 'api/category/retrieveall',
					'params' => array()
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	public function LeavewordAction()
    {
    	$params = array(
				array(
					'name' => 'leaveword',
					'url' => 'api/leaveword',
					'params' => array(
					'word'
					)
				),
				array(
					'name' => 'manage',
					'url' => 'api/leavewordmanage',
					'params' => array(
					)
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	
	public function PushAction()
    {
    	$params = array(
				array(
					'name' => 'pushpic',
					'url' => 'api/pushpic',
					'params' => array(
					)
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }

	public function usercenterAction()
    {
    	$params = array(
				array(
					'name' => 'retireveall',
					'url' => 'api/user/retrieveall',
					'params' => array()
				),
				array(
					'name' => 'retirevealladmin',
					'url' => 'api/user/retrievealladmin',
					'params' => array()
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	public function businessAction()
    {
    	$params = array(
				array(
					'name' => 'index',
					'url' => 'api/business',
					'params' => array()
				),
				array(
					'name' => 'start_date',
					'url' => 'api/business/start_date',
					'params' => array(
								'start_date',
								'machine_id'
					)
				),
				array(
					'name' => 'end_date',
					'url' => 'api/business/end_date',
					'params' => array(
								'end_date',
								'machine_id',
								'money'
					)
				),
				array(
					'name' => 'status',
					'url' => 'api/business/status',
					'params' => array(
								'machine_id'
					)
				),
				array(
					'name' => 'count',
					'url' => 'api/business/count',
					'params' => array(
								'machine_id',
								'start_search_date',
								'end_search_date'
					)
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	public function brandAction()
    {
    	$params = array(
				array(
					'name' => 'retire',
					'url' => 'api/brand/retrieve',
					'params' => array(
					             'category_id'
								)
				),
				array(
					'name' => 'info',
					'url' => 'api/brand/info',
					'params' => array(
					       'brand_id'
								)
				),
				array(
					'name' => 'update',
					'url' => 'api/brand/update',
					'params' => array(
					       'brand_id',
						   'category',
						   'brand_name'
								)
				),
				array(
					'name' => 'del',
					'url' => 'api/brand/del',
					'params' => array(
					       'brand_id'
								)
				),
				array(
					'name' => 'add',
					'url' => 'api/brand/add',
					'params' => array(
					       'category',
						   'brand_name'
						   )
				)
				
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	
        public function userAction()
    {
        
		$params = array(
				array(
					'url' => 'userprofile',
					'params' => array(
								)
				),
				array(
					'url' => 'userprofilebyid',
					'params' => array(
							'uid'
								)
				),
				array(
					'url' => 'active',
					'params' => array(
							'username'
								)
				),
				array(
					'url' => 'deactive',
					'params' => array(
							'username'
								)
				),
				array(
					'url' => 'changeprofile',
					'params' => array(
							'nickname',
							'signature',
							'phone'
								)
				),
				array(
					'url' => 'userlogin',
					'params' => array(
					       'username',
					       'password'
								)
				),
				array(
					'url' => 'logout',
					'params' => array(
								)
				),
				array(
					'url' => 'gettoken',
					'params' => array(
								)
				),
				array(
					'url' => 'gettelvalidate',
					'params' => array(
								'username'
								)
				),
				array(
					'url' => 'usersendvalidate',
					'params' => array(
								)
				),
				array(
					'url' => 'userregister',
					'params' => array(
					        'phone',
					        'password',
							'nickname',
					        'validate'
								)
				),
				array(
					'url' => 'getpasswordtoken',
					'params' => array(
							'username'
								)
				),
				array(
					'url' => 'userchangepassword',
					'params' => array(
							'username',
							'newpwd',
							'validate'
								)
				)
		);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
    
    public function PostAction()
    {
        return $this->render('YdzyTestBundle:Default:post.html.twig');
    }
    
	public function userAdminAction()
    {
        $urlProfix = 'admin/user/';
		$params = array(
				array(
					'url' => $urlProfix.'create',
					'params' => array(
									'username',
									'email',
									'phone',
									'validate'
								)
				),
				array(
					'url' => $urlProfix.'retrieve',
					'params' => array(
					                'userid'
								)
				),
				array(
					'url' => $urlProfix.'list',
					'params' => array(
									'username',
									'email',
									'phone',
									'begin',
									'count'
								)
				),
				array(
					'url' => $urlProfix.'edit',
					'params' => array(
									'username',
									'email',
									'phone',
									'validate'
								)
				),
				array(
					'url' => $urlProfix.'delete',
					'params' => array(
									'userid'
								)
				),
				array(
					'url' => $urlProfix.'login',
					'params' => array(
									'username',
									'password'
								)
				),
				array(
					'url' => $urlProfix.'logout',
					'params' => array(
									
								)
				)
			);
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
	}

        

	

	
	public function areaAction()
    {
    	$params = array(
				
			array(
					'name' => 'retrieve',
					'url' => 'api/area/retrieve',
					'params' => array(
									'bak_date'
									)
				),
			array(
					'name' => 'retrievePro',
					'url' => 'api/area/retrievePro',
					'params' => array()
				),
			array(
					'name' => 'retrieveAllProLocation',
					'url' => 'api/area/retrieveAllProLocation',
					'params' => array(
						)
				),
			array(
					'name' => 'retrieveAllCityLocation',
					'url' => 'api/area/retrieveAllCityLocation',
					'params' => array(
									'province'
						)
				),
			array(
					'name' => 'retrieveAllMachine',
					'url' => 'api/area/retrieveAllMachine',
					'params' => array(
									'mark',
									'province',
									'city',
									'category_id',
									'lng',
									'lat',
									'distance',
									'tonn_min',
									'tonn_max',
									'workhours_min',
									'workhours_max',
									'sale_price_min',
									'sale_price_max',
									'factory_year_min',
									'factory_year_max',
									'keyword'
									
						)
				),
			array(
					'name' => 'retrieve',
					'url' => 'api/area/retrieveId',
					'params' => array(
									'city'
									)
				),
				array(
					'name' => 'retrieveCityByLocation',
					'url' => 'api/area/retrieveCityByLocation',
					'params' => array(
									'lng',
									'lat',
									'lng1',
									'lat1'
									)
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	
	 public function driverAction()
    {
    	$params = array(
				
			array(
					'name' => 'retrieve',
					'url' => 'api/driver/retrieve',
					'params' => array(
									'uid',
									'category_id',
									'recommend',
									'keyword', //关键词搜索，不填不馊索
									'salary_sort', //薪酬排序，0倒序，1正序 默认填写0排序
									'updated_date_sort',
									'workyears_min',//工作经验最小值
									'workyears_max',//工作经验最大值
									'province_id',
									'city_id',
									'start', //list开始条目
									'num' //要调取的条目
																		)
				),
				array(
					'name' => 'info',
					'url' => 'api/driver/info',
					'params' => array(
									'driver_id'
					                 )
				),
				array(
					'name' => 'del',
					'url' => 'api/driver/del',
					'params' => array(
									'driver_id'
					                 )
				),
				array(
					'name' => 'create',
					'url' => 'api/driver/create',
					'params' => array(
									'user_id',
									'category',
									'model',
									'image_url',
									'salary',
									'workyears_min',
									'workyears_max',
									'province_id',
									'city_id',
									'description'
					                 )
				),
				array(
					'name' => 'update',
					'url' => 'api/driver/update',
					'params' => array(
									'driver_id',
									'mark',  //1已经聘用 0正在招募 -1下线
									'user_id',
									'category_id',//类别id
									'image_url',//图片上传成功返回的id，招聘只能上传一张，返回唯一id
									'salary',//薪资
									'model',//机型  如 小松PC60-7
									'workyears_min', //工作经验 填写int类型数据
									'workyears_max',
									'province_id',
									'city_id',
									'description',//详细描述
									'linker',
									'linker_tel'
					                 )
				)
			);
		
		return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
    
    public function machineAction()
    {
		$params = array(
				array(
					'name' => 'retrieveAll',
					'url' => 'api/machine/retrieveAll',
					'params' => array(
					                 )
				),
				array(
				    'name' => 'retrieveByFilter',
					'url' => 'api/machine/retrieveByFilter',
					'params' => array(
					                //所有项目都可为空
									'machine_id', //机器id
									'uid',//用户的id
									'category_id', //类别id 0表示其他类别，除挖掘机和装载机 为空搜索其他类别
									'keyword', //搜索关键词,默认不搜索
									'brand_id', //品牌名称 为空查询所有品牌
									'mark', //标识，0出租，1出售，2已经出租 3已经出售 -2下线
									'recommend', //0未推荐 1推荐 默认全部
									'province', //筛选时，省id
									'city', //筛选时，市id
									'tonn_min', //筛选时，吨位最小值，为空默认是0
									'tonn_max', //筛选时，吨位最大值，为空默认是99999
									'workhours_min', //筛选时，工作小时数最小值，为空默认为0
									'workhours_max', //筛选时，工作小时数最大值，为空默认为99999
									'lat', //当前定位的经纬度
									'lng',
									'distance', //查找附近功能，单位km,默认为0
									'factory_year_min', //出厂年限的最小值，默认0
									'factory_year_max',  //出厂年限最大值，默认100
									'sale_price_min', //筛选时，售价最小值，为空默认为0
									'sale_price_max', //筛选时，售价最大值，为空默认为99999
									'rent_by_month',//月租金排序 0倒序 1正序 为空不排序
									'rent_by_num', //台租金排序
									'sale_price_sort', //售价排序
									'distance_sort',  //距离排序
									'workhours_sort', //工作小时数排序
									'factory_year_sort', //出厂年限排序
									'updated_date_sort',  //按照更新时间排序
									'start', //list开始条目
									'num' //list调取数目
					                 )
				),
				array(
					'name' => 'info',
					'url' => 'api/machine/info',
					'params' => array(
									'machine_id'
					                 )
				),
				array(
					'name' => 'recommend',
					'url' => 'api/machine/recommend',
					'params' => array(
									'machine_id'
									//'recommend_status'
					                 )
				),
				array(
					'name' => 'del',
					'url' => 'api/machine/del',
					'params' => array(
									'machine_id'
					                 )
				),
				array(
					'name' => 'create',
					'url' => 'api/machine/create',
					'params' => array(
									'mark',    //标识，0出租，1出售，2已经出租 3已经出售 -2下线
									'category', //类别的id
									'machine_name', //标题，机器名 6-30个字
									'image_url', //图片上传成功返回的id，格式：3，4，5，6
									'image_url2',
									'sale_price', //售价
									'rent_by_num', //台班租价
									'rent_by_month', //月租价
									'brand_id',  //品牌id
									'brand_model', //自己填写的品牌名
									'factory_year', //生产日期，格式：199009
									'workhours',  //工作小时数
									'tonn',    //吨位
									'province_id', //省id
									'city_id',  //城市id
									'description', //描述
									'lat', //定位坐标
									'lng',
									'address', //定位填写地址
									'linker',
									'linker_tel'
					                 )
				),
				array(
					'name' => 'update',
					'url' => 'api/machine/update',
					'params' => array(
									'machine_id',
									'mark',
									'category',
									'machine_name',
									'image_url',
									'image_url2',
									'sale_price',
									'rent_by_num',
									'rent_by_month',
									'brand_id',
									'brand_model',
									'factory_year',
									'workhours',
									'tonn',
									'province_id',
									'city_id',
									'description',
									'lat',
									'lng',
									'address',
									'trueprice',  //实际售价
									'saleway', //售出方式
									'linker',
									'linker_tel'
					                 )
				)
			);

        return $this->render('YdzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }
	
	// for image upload only
	public function imageAction()
	{
		return $this->render('YdzyTestBundle:Default:upload.html.twig');
	}
	
}
