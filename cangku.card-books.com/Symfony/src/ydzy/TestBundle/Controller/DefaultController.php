<?php

namespace ydzy\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ydzyTestBundle:Default:index.html.twig');
    }

    public function menuAction()
    {
        return $this->render('ydzyTestBundle:Default:menu.html.twig');
    }

    public function downloadAction()
    {
    	$json_r = array(
    			array(
    					'name' => 'game1', 'url' => 'http://223.4.145.218/upload/1b61/0270/9fdf/9332/0b91/08a7/d32b/1df6/MyCards.apk'
    				),
    			array(
    					'name' => 'game2', 'url' => 'http://223.4.145.218/upload/2b44/073c/36b1/720a/66b3/c3bd/2d0a/e9ba/gym.apk'
    				),
    			array(
    					'name' => 'game3', 'url' => 'http://223.4.145.218/upload/288b/75c6/fbeb/03c4/faf9/6dbc/b007/8c71/car.apk'
    				)
    		);

    	$response = new Response(json_encode($json_r), 200);
		$response->headers->set('content-type','application/json');

		return $response;
    }

	public function userAction()
    {
		$params = array(
				array(
					'name' => 'create',
					'url' => 'api/user/create',
					'params' => array(
									'nickname',
									'phoneNumber',
									'emailAddr',
									'roleId',
									'password',
									'realname',
									'idCard',
									'address'
								)
				),
				array(
					'name' => 'delete',
					'url' => 'api/user/delete',
					'params' => array(
									'userId'
								)
				),
				array(
					'name' => 'login',
					'url' => 'api/user/login',
					'params' => array(
									'criteria',
									'appId',
									'password',
									'apnsToken'
								)
				),
				array(
					'name' => 'update apns token',
					'url' => 'user/update/token',
					'params' => array('apnsToken')
				),
				array(
					'name' => 'logout',
					'url' => 'user/logout',
					'params' => array()
				),
				array(
					'name' => 'retrieve user info',
					'url' => 'user/retrieve',
					'params' => array(
									'userId'
								)
				),
				array(
					'name' => 'modify password',
					'url' => 'user/modify/password',
					'params' => array(
									'userId',
									'oldPassword',
									'newPassword'
								)
				),
				array(
					'name' => 'modify user',
					'url' => 'user/modify',
					'params' => array(
									'userId',
									'phoneNumber',
									'emailAddr',
									'roleId',
									'realname',
									'sex',
									'birthDate',
									'idCard',
									'address'
								)
				),
				array(
					'name' => 'reset password',
					'url' => 'user/reset/password',
					'params' => array(
									'mailAddr'
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }

	public function groupAction()
    {
		$params = array(
				array(
					'name' => 'create',
					'url' => 'group/create',
					'params' => array(
									'merchantId',
									'groupName',
									'cardList' => array('cardId1', 'cardId2')
								)
				),
				array(
					'name' => 'delete',
					'url' => 'group/delete',
					'params' => array(
									'groupId'
								)
				),
				array(
					'name' => 'modify',
					'url' => 'group/modify',
					'params' => array(
									'groupId',
									'groupName'
								)
				),
				array(
					'name' => 'retrieve',
					'url' => 'group/retrieve',
					'params' => array(
									'groupId'
								)
				),
				array(
					'name' => 'list cards',
					'url' => 'group/list/cards',
					'params' => array(
									'groupId'
								)
				),
				array(
					'name' => 'assign cards',
					'url' => 'group/assign/cards',
					'params' => array(
									'groupId',
									'cardList' => array('cardId1', 'cardId2')
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }

	public function promosAction()
    {
        $urlProfix = 'api/promo';
		$params = array(
				array(
					'url' => 'create',
					'params' => array(
									'promoName',
									'descrption',
									'price',
									'imgs' => array('cardId1', 'cardId2')
								)
				),
				array(
					'url' => 'delete',
					'params' => array(
									'promosId'
								)
				),
				array(
					'url' => 'edit',
					'params' => array(
									'promoId',
									'promoName',
									'descrption',
									'price',
									'imgs' => array('cardId1', 'cardId2')
								)
				),
				array(
					'url' => 'retrieve',
					'params' => array(
									'promoId'
								)
				),
				array(
					'url' => 'list',
					'params' => array(
									
								)
				),
				array(
					'url' => 'cart',
					'params' => array(
									'promoid',
									'amount'
								)
				),
				array(
					'url' => 'transfer',
					'params' => array(
					                'userid',
									'promos' => array(
									    array(
									    'promoid',
									    'amount'
									),
									array(
									    'promoid',
									    'amount'
									)
									)
								)
				),
				array(
					'url' => 'my',
					'params' => array(
								)
				),
				array(
					'url' => 'users',
					'params' => array(
									'userid'
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params, 'urlProfix' => $urlProfix));
    }

	public function merchantAction()
    {
		$params = array(
				array(
					'name' => 'create',
					'url' => 'merchant/create',
					'params' => array(
									'name',
									'expireDate',
									'type',
									'classificationId',
									'location',
									'operatorId',
									'permission',
									'queryInterval'
								)
				),
				array(
					'name' => 'delete',
					'url' => 'merchant/delete',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'suspend',
					'url' => 'merchant/suspend',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'resume',
					'url' => 'merchant/resume',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'modify',
					'url' => 'merchant/modify',
					'params' => array(
									'merchantId',
									'name',
									'expireDate',
									'type',
									'classificationCode',
									'location',
									'permission',
									'queryInterval'
								)
				),
				array(
					'name' => 'upload textimage',
					'url' => 'merchant/upload/textimage',
					'params' => array(
									'merchantId',
									'typeId',
									'title',
									'content',
									'originalUrl',
									'imageUrl',
									'imageThumbnailUrl',
									'order'
								)
				),
				array(
					'name' => 'retrieve',
					'url' => 'merchant/retrieve',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'introduce get',
					'url' => 'merchant/introduce/get',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'introduce set',
					'url' => 'merchant/introduce/set',
					'params' => array(
									'merchantId', 'address', 'phoneNumber', 'mapUrl'
								)
				),
				array(
					'name' => 'message get',
					'url' => 'merchant/message/get',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'message set',
					'url' => 'merchant/message/set',
					'params' => array(
									'merchantId', 'preRegister', 'postRegister'
								)
				),
				array(
					'name' => 'list cperators',
					'url' => 'merchant/list/operators',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'list free operators',
					'url' => 'merchant/list/free/operators',
					'params' => array()
				),
				array(
					'name' => 'assign cperators',
					'url' => 'merchant/assign/operators',
					'params' => array(
									'merchantId',
									'operatorList' => array('operatorId1', 'operatorId2')
								)
				),
				array(
					'name' => 'list croups',
					'url' => 'merchant/list/groups',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'list categories',
					'url' => 'merchant/list/categories',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'list cards',
					'url' => 'merchant/list/cards',
					'params' => array(
									'merchantId'
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }

	public function cardAction()
    {
		$params = array(
				array(
					'name' => 'list by merchant',
					'url' => 'card/list/by/merchant',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'list by user',
					'url' => 'card/list/by/user',
					'params' => array(
									'userId'
								)
				),
				array(
					'name' => 'retrieve',
					'url' => 'card/retrieve',
					'params' => array(
									'cardId'
								)
				),
				array(
					'name' => 'list groups',
					'url' => 'card/list/groups',
					'params' => array(
									'cardId'
								)
				),
				array(
					'name' => 'assign groups',
					'url' => 'card/assign/groups',
					'params' => array(
									'cardId',
									'groupList' => array('groupId1', 'groupId2')
								)
				),
				array(
					'name' => 'retrieve category',
					'url' => 'card/retrieve/category',
					'params' => array(
									'cardId'
								)
				),
				array(
					'name' => 'assign category',
					'url' => 'card/assign/category',
					'params' => array(
									'cardId',
									'categoryId'
								)
				),
				array(
					'name' => 'active',
					'url' => 'card/activate',
					'params' => array(
									'userId',
									'merchantId'
								)
				),
				array(
					'name' => 'deactivate',
					'url' => 'card/deactivate',
					'params' => array(
									'cardId'
								)
				),
				array(
					'name' => 'create',
					'url' => 'card/create',
					'params' => array(
									'merchantId',
									'userId',
									'number',
									'type',
									'amount',
									'expireDate',
									'avatar',
									'points',
									'realname',
									'age',
									'phoneNumber',
									'idCard',
									'emailAddr',
									'categoryId',
									'groupId'
								)
				),
				array(
					'name' => 'modify',
					'url' => 'card/modify',
					'params' => array(
									'cardId',
									'number',
									'type',
									'amount',
									'expireDate',
									'avatar',
									'points',
									'realname',
									'age',
									'phoneNumber',
									'idCard',
									'emailAddr',
									'categoryId',
									'groupId'
								)
				),
				array(
					'name' => 'delete',
					'url' => 'card/delete',
					'params' => array(
									'cardId'
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }

	public function noticeAction()
    {
		$params = array(
				array(
					'name' => 'create notice',
					'url' => 'notice/create',
					'params' => array(
									'merchantId',
									'typeId',
									'title',
									'content',
									'originalUrl',
									'imageUrl',
									'imageThumbnailUrl',
									'order'
								)
				),
				array(
					'name' => 'modify notice',
					'url' => 'notice/modify',
					'params' => array(
									'noticeId',
									'typeId',
									'title',
									'content',
									'originalUrl',
									'imageUrl',
									'imageThumbnailUrl',
									'order'
								)
				),
				array(
					'name' => 'delete notice',
					'url' => 'notice/delete',
					'params' => array(
									'noticeId'
								)
				),
				array(
					'name' => 'list summary by merchant',
					'url' => 'notice/list/summary/by/merchant',
					'params' => array(
									'merchantId'
								)
				),
				array(
					'name' => 'list summary by type',
					'url' => 'notice/list/summary/by/type',
					'params' => array(
									'userId',
									'typeId'
								)
				),
				array(
					'name' => 'list notices',
					'url' => 'notice/list',
					'params' => array(
									'merchantId',
									'typeId',
									'creationDate',
									'num'
								)
				),
				array(
					'name' => 'retrieve notices',
					'url' => 'notice/retrieve',
					'params' => array(
									'noticeId'
								)
				)
			);

        return $this->render('ydzyTestBundle:Default:testcase.html.twig', array('params' => $params));
    }



	// for image upload only
	public function uploadAction()
	{
		return $this->render('ydzyTestBundle:Default:upload.html.twig');
	}

}
