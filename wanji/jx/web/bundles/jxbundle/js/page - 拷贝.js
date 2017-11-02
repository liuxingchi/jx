function QueryString(){
	var currentPage = $("#currentPage").val();
	if(currentPage==""){currentPage = 1;}
	//var sValue=currentPage.search.match(new RegExp("[\?\&]"+item+"=([^\&]*)(\&?)","i"));
	return currentPage
}

function loadpage(oClick){
	var start = oClick.title;
	var startnum = (start-1)*10+"";
	$("#currentPage").val(oClick.title);
	
	var json = {
		"mark": "1",
		"keyword":KEYWORD,
		"brand_id":BRAND,
		"category_id":CATEGORY,
		"province":PROVINCE,
		"workhours_min":WORKHOURS_MIN,
		"workhours_max":WORKHOURS_MAX,
		"tonn_min":TONN_MIN,
		"tonn_max":TONN_MAX,
		"sale_price_min":PRICE_MIN,
		"sale_price_max":PRICE_MAX,
		'factory_year_min':FACTORY_MIN,
		'factory_year_max':FACTORY_MAX,
		"updated_date_sort":DATESORT,
		"workhours_sort":WORKHOURSSORT,
		"sale_price_sort":PRICESORT,
		"factory_year_sort":FACTORYSORT,
		"start": startnum,
		"num": "10"
		};

reloadList(json,1);

}
function showPage(count,current){
				var count = count;
				var current = current;
				var perpage = 10; //每页个数
				if(current==0){
					var currentpage = parseInt(1);
					}else{
						var currentpage = QueryString();
						currentpage = parseInt(currentpage);//当前页
					}
				var pagecount = Math.ceil(count/perpage); //总页数
				var pagestr = ""; //分页显示内容
				var breakpage = 9;
				var currentposition = 4;
				var breakspace = 2;
				var maxspace = 4;
				var prevnum = currentpage-currentposition;
				var nextnum = currentpage+currentposition;
				if(prevnum<1) prevnum = 1;
				if(nextnum>pagecount) nextnum = pagecount;
pagestr += (currentpage==1)?'<span class="prev"></span>':'<span class="prev"><a href="?page='+(currentpage-1)+'"></a></span>';
				if(prevnum-breakspace>maxspace){
					for(i=1;i<=breakspace;i++)
					pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
					pagestr += '<li class="break"><a href="#">...</a></li>';
					for(i=pagecount-breakpage+1;i<prevnum;i++)
						pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
				}else{
					for(i=1;i<prevnum;i++)
						pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
				}
				for(i=prevnum;i<=nextnum;i++){
					pagestr += (currentpage==i)?'<li class="active"><a href="#">'+i+'</a></li>':'<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
				}
				if(pagecount-breakspace-nextnum+1>maxspace){
					for(i=nextnum+1;i<=breakpage;i++)
						pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
					pagestr += '<li class="break"><a>...</a></li>';
					for(i=pagecount-breakspace+1;i<=pagecount;i++)
						pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
				}else{
					for(i=nextnum+1;i<=pagecount;i++)
						pagestr += '<li><a " onclick="loadpage(this)" title='+i+'>'+i+'</a></li>';
				}
				pagestr += (currentpage==pagecount)?'<span class="next"></span>':'<span class="next"><a href="?page='+(currentpage+1)+'"></a></span>';
				document.getElementById("pagebar").innerHTML = pagestr;
	
	}
function page(current){
	
	var current = current;
	//获得总数据量
	var json = {
		"mark":1,
		"category_id":CATEGORY,
		"brand_id":BRAND,
		"province":PROVINCE,
		"city":CITY,
		"workhours_min":WORKHOURS_MIN,
		"workhours_max":WORKHOURS_MAX,
		"tonn_min":TONN_MIN,
		"tonn_max":TONN_MAX,
		"sale_price_min":PRICE_MIN,
		"sale_price_max":PRICE_MAX,
		'factory_year_min':FACTORY_MIN,
		'factory_year_max':FACTORY_MAX,
		"keyword":KEYWORD		
		};
            ajaxPost(
              'app_dev.php/api/area/retrieveAllMachine',
              json,
              function(data, textStatus){
				
           		var num = data.length;//总条数
				if(num==undefined){var count = 0;}else{var count = num;}
				
				if(current == 0)
					{
					this.showPage(count,0)
					}
				else{
					this.showPage(count,1);
					}
				},
              function(XMLHttpRequest, textStatus, errorThrown){
                alert("加载页码出现错误，请刷新重试");
              }
          );
	
	
	}
