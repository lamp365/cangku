<?php

namespace Home\controller;
use Think\Controller;
use QL\QueryList;

class CaijiController extends Controller{

    public function getCont(){
        header("Content-Type: text/html;charset=utf-8");
        set_time_limit(0);
        $goods_arr = array(
          0 => array('good_url'=>"http://www.ruijiafushi.com/shop_gln1_a1/1440/products.aspx?sku=754607&shbid=6741"),
          1 => array('good_url'=>"http://www.ruijiafushi.com/shop_gln1_a1/1440/products.aspx?sku=754506&shbid=6741"),
          2 => array('good_url'=>"http://www.ruijiafushi.com/shop_gln1_a1/1440/products.aspx?sku=754504&shbid=6741"),
          3 => array('good_url'=>"http://www.ruijiafushi.com/shop_gln1_a1/1440/products.aspx?sku=754498&shbid=6741"),
        );
        $loop = 0;
        foreach($goods_arr as $one_item){
            $run_url  = $one_item['good_url'];
            $url_info = parse_url($run_url);
            $arr_info = array();
            parse_str($url_info['query'], $arr_info);
            $sku = $arr_info['sku'];
            $caiji_rule = array(
                //采集文章标题
                'title' => array('#Product_main1_Product_top21_Label1','text'),
                'content' => array('#Product_main1_Product_bottom1_Span2 .SPSX_hang2','html')
            );


            //手动转码
//            $html = iconv('GBK','UTF-8',file_get_contents($run_url));

            //按照用户设置的规则采集数据
            $result =  QueryList::Query($run_url,$caiji_rule,'','UTF-8','GBK')->getData(function($item) use($run_url){
                foreach($item as $key => &$content){
                    if($key == 'content'){
                        $piclist = array();
                        $pic_info = QueryList::Query($content,array(
                            'the_pic'  => array('img','src'),
                        ),'')->data;
                        if(!empty($pic_info)){
                            foreach($pic_info as $the_one){
                                $piclist[] = 'http://www.ruijiafushi.com'.$the_one['the_pic'];
                            }
                        }
                        $item[$key] = $piclist;
                    }
                }// foreach end
                return $item;
            });
            $dir = './demo/'.$sku;
            mkdirs($dir);
            $dir  = $_SERVER['DOCUMENT_ROOT'].'/demo/'.$sku;
            foreach ($result[0]['content'] as $key => $one_pic){
                //下载图片
                $img = file_get_contents($one_pic);
                $name = $key.".jpg";

                $file = $dir.'/'.$name;
                file_put_contents($file,$img);
            }

//            $title = iconv("GBK","UTF-8",$result[0]['title']);

            $cont_data = $run_url.PHP_EOL.$result[0]['title'];
            $file = $dir.'/001.txt';
            file_put_contents($file,$cont_data);

            if($loop%8==0){
                sleep(1);
            }
            $loop++;
        }
//        ppd($result);
        ppd('wanbi');

    }

    public function getlist(){
        header("Content-Type: text/html;charset=utf-8");
        set_time_limit(0);
        $page = 1;
        $length = $page+1;
        for($page;$page<=$length;$page++){
            $run_url = "http://www.ruijiafushi.com/shop_gln1_a1/1440/Products_List.aspx?page={$page}&shbid=6741";
            $caiji_rule = array(
                //采集文章标题
                'content' => array('#ProductList1_Repeater1 .SPSX_STYLE1','text'),
                'url'     => array('#ProductList1_Repeater1 .SPSX_bian a','href')
            );
            //按照用户设置的规则采集数据
            $result =  QueryList::Query($run_url,$caiji_rule,'','UTF-8','GBK')->getData();
            foreach ($result as $row){
                $list_url = "http://www.ruijiafushi.com/shop_gln1_a1/1440/".$row['url'];
                $title    = $row['content'];
                if(strpos($title,'缺') || strpos($title,'暂') || strpos($title,'断')){
                    continue;
                }
                $url_info = parse_url($list_url);
                $arr_info = array();
                parse_str($url_info['query'], $arr_info);
                $sku = $arr_info['sku'];
                $data = array();
                $data['title']    = $title;
                $data['list_url'] = $list_url;
                $data['page']     = $page;
                $data['good_id']       = $sku;
                $data['type']     = 1;
                $data['read']     = 0;
                $res = M("caiji")->where("good_id='{$sku}'")->find();
                if(!$res){
                    echo "{$sku}---{$list_url}[完毕]<br/>";
                    M('caiji')->add($data);
                }
            }
        }
        echo 'finish!!';
    }

    public function demo()
    {
        $page = '<div id="home" class="floor floor-home floor-load">
        <div class="floor-inner">
            <div class="floor-head"><strong>居家用品</strong></div>
            <div class="floor-body">

                <div class="floor-body-inner clearfix">
                    <div class="cate-second">

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|29645|&amp;rp=30069|0&amp;ff=home|0|1|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/185/003a19fe-c50c-4f84-b6f0-6dec083acae6.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/185/003a19fe-c50c-4f84-b6f0-6dec083acae6.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|29645|&amp;rp=30069|0&amp;ff=home|0|1|0" target="_blank">家居家纺</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|29873||&amp;rp=30069|29645&amp;ff=home|0|1|1" target="_blank">床品套件</a><a href="//category.vip.com/search-1-0-1.html?q=3|70497||&amp;rp=30069|29645&amp;ff=home|0|1|2" target="_blank">磨毛套件</a><a href="//category.vip.com/search-1-0-1.html?q=3|29877||&amp;rp=30069|29645&amp;ff=home|0|1|3" target="_blank">床用单品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29625||&amp;rp=30069|29645&amp;ff=home|0|1|4" target="_blank">被子</a><a href="//category.vip.com/search-1-0-1.html?q=3|30727||&amp;rp=30069|29645&amp;ff=home|0|1|5" target="_blank">毛毯</a><a href="//category.vip.com/search-1-0-1.html?q=3|29870||&amp;rp=30069|29645&amp;ff=home|0|1|6" target="_blank">枕头</a><a href="//category.vip.com/search-1-0-1.html?q=3|29876||&amp;rp=30069|29645&amp;ff=home|0|1|7" target="_blank">抱枕/靠垫</a><a href="//category.vip.com/search-1-0-1.html?q=3|29871||&amp;rp=30069|29645&amp;ff=home|0|1|8" target="_blank">毛巾/浴巾</a><a href="//category.vip.com/search-1-0-1.html?q=3|29875||&amp;rp=30069|29645&amp;ff=home|0|1|9" target="_blank">床垫</a><a href="//category.vip.com/search-1-0-1.html?q=3|44444||&amp;rp=30069|29645&amp;ff=home|0|1|10" target="_blank">床褥</a><a href="//category.vip.com/search-1-0-1.html?q=3|29869||&amp;rp=30069|29645&amp;ff=home|0|1|11" target="_blank">窗帘窗纱</a><a href="//category.vip.com/search-1-0-1.html?q=3|29646||&amp;rp=30069|29645&amp;ff=home|0|1|12" target="_blank">女式拖鞋</a><a href="//category.vip.com/search-1-0-1.html?q=3|29647||&amp;rp=30069|29645&amp;ff=home|0|1|13" target="_blank">男式拖鞋</a><a href="//category.vip.com/search-1-0-1.html?q=3|30730||&amp;rp=30069|29645&amp;ff=home|0|1|14" target="_blank">居家服</a><a href="//category.vip.com/search-1-0-1.html?q=3|29745||&amp;rp=30069|29645&amp;ff=home|0|1|15" target="_blank">男款家居服</a><a href="//category.vip.com/search-1-0-1.html?q=3|29744||&amp;rp=30069|29645&amp;ff=home|0|1|16" target="_blank">女款居家服</a><a href="//category.vip.com/search-1-0-1.html?q=3|37685||&amp;rp=30069|29645&amp;ff=home|0|1|17" target="_blank">凉席</a><a href="//category.vip.com/search-1-0-1.html?q=3|37686||&amp;rp=30069|29645&amp;ff=home|0|1|18" target="_blank">蚊帐</a><a href="//category.vip.com/search-1-0-1.html?q=3|29874||&amp;rp=30069|29645&amp;ff=home|0|1|19" target="_blank">儿童床品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29872||&amp;rp=30069|29645&amp;ff=home|0|1|20" target="_blank">婚庆床品</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|29999|&amp;rp=30069|0&amp;ff=home|0|2|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/8/87fa6881-d4be-4a8c-ab55-9a52af6501b6.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/8/87fa6881-d4be-4a8c-ab55-9a52af6501b6.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|29999|&amp;rp=30069|0&amp;ff=home|0|2|0" target="_blank">家私家具</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|29867||&amp;rp=30069|29999&amp;ff=home|0|2|1" target="_blank">沙发</a><a href="//category.vip.com/search-1-0-1.html?q=3|29878||&amp;rp=30069|29999&amp;ff=home|0|2|2" target="_blank">布艺家具</a><a href="//category.vip.com/search-1-0-1.html?q=3|29805||&amp;rp=30069|29999&amp;ff=home|0|2|3" target="_blank">边桌茶几</a><a href="//category.vip.com/search-1-0-1.html?q=3|29868||&amp;rp=30069|29999&amp;ff=home|0|2|4" target="_blank">床</a><a href="//category.vip.com/search-1-0-1.html?q=3|29859||&amp;rp=30069|29999&amp;ff=home|0|2|5" target="_blank">餐桌椅</a><a href="//category.vip.com/search-1-0-1.html?q=3|29863||&amp;rp=30069|29999&amp;ff=home|0|2|6" target="_blank">衣帽架/储物架</a><a href="//category.vip.com/search-1-0-1.html?q=3|29860||&amp;rp=30069|29999&amp;ff=home|0|2|7" target="_blank">衣柜衣橱</a><a href="//category.vip.com/search-1-0-1.html?q=3|29866||&amp;rp=30069|29999&amp;ff=home|0|2|8" target="_blank">床头柜/斗柜</a><a href="//category.vip.com/search-1-0-1.html?q=3|29865||&amp;rp=30069|29999&amp;ff=home|0|2|9" target="_blank">电脑桌/书桌</a><a href="//category.vip.com/search-1-0-1.html?q=3|37688||&amp;rp=30069|29999&amp;ff=home|0|2|10" target="_blank">晾衣架</a><a href="//category.vip.com/search-1-0-1.html?q=3|29864||&amp;rp=30069|29999&amp;ff=home|0|2|11" target="_blank">阳台实用</a><a href="//category.vip.com/search-1-0-1.html?q=3|29809||&amp;rp=30069|29999&amp;ff=home|0|2|12" target="_blank">电视柜</a><a href="//category.vip.com/search-1-0-1.html?q=3|29861||&amp;rp=30069|29999&amp;ff=home|0|2|13" target="_blank">餐边柜</a><a href="//category.vip.com/search-1-0-1.html?q=3|29858||&amp;rp=30069|29999&amp;ff=home|0|2|14" target="_blank">梳妆台/凳</a><a href="//category.vip.com/search-1-0-1.html?q=3|29862||&amp;rp=30069|29999&amp;ff=home|0|2|15" target="_blank">商业办公</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|30003|&amp;rp=30069|0&amp;ff=home|0|3|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/52/1a60a71f-bc44-4f8a-9ed8-df4108bdcc94.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/52/1a60a71f-bc44-4f8a-9ed8-df4108bdcc94.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|30003|&amp;rp=30069|0&amp;ff=home|0|3|0" target="_blank">厨房用具</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|29899||&amp;rp=30069|30003&amp;ff=home|0|3|1" target="_blank">厨房收纳</a><a href="//category.vip.com/search-1-0-1.html?q=3|37683||&amp;rp=30069|30003&amp;ff=home|0|3|2" target="_blank">烧烤</a><a href="//category.vip.com/search-1-0-1.html?q=3|29894||&amp;rp=30069|30003&amp;ff=home|0|3|3" target="_blank">锅/壶</a><a href="//category.vip.com/search-1-0-1.html?q=3|37679||&amp;rp=30069|30003&amp;ff=home|0|3|4" target="_blank">烹饪用具</a><a href="//category.vip.com/search-1-0-1.html?q=3|37680||&amp;rp=30069|30003&amp;ff=home|0|3|5" target="_blank">碗筷餐具</a><a href="//category.vip.com/search-1-0-1.html?q=3|37681||&amp;rp=30069|30003&amp;ff=home|0|3|6" target="_blank">保鲜盒</a><a href="//category.vip.com/search-1-0-1.html?q=3|29896||&amp;rp=30069|30003&amp;ff=home|0|3|7" target="_blank">刀剪具</a><a href="//category.vip.com/search-1-0-1.html?q=3|37682||&amp;rp=30069|30003&amp;ff=home|0|3|8" target="_blank">烘焙</a><a href="//category.vip.com/search-1-0-1.html?q=3|29897||&amp;rp=30069|30003&amp;ff=home|0|3|9" target="_blank">餐具</a><a href="//category.vip.com/search-1-0-1.html?q=3|29898||&amp;rp=30069|30003&amp;ff=home|0|3|10" target="_blank">厨用小工具</a><a href="//category.vip.com/search-1-0-1.html?q=3|29900||&amp;rp=30069|30003&amp;ff=home|0|3|11" target="_blank">烧烤烘焙用品</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|30000|&amp;rp=30069|0&amp;ff=home|0|4|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/145/21bee38e-e146-45cc-a375-9ba90a8e78ab.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/145/21bee38e-e146-45cc-a375-9ba90a8e78ab.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|30000|&amp;rp=30069|0&amp;ff=home|0|4|0" target="_blank">家居装饰</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|29880||&amp;rp=30069|30000&amp;ff=home|0|4|1" target="_blank">挂钟</a><a href="//category.vip.com/search-1-0-1.html?q=3|29881||&amp;rp=30069|30000&amp;ff=home|0|4|2" target="_blank">墙贴</a><a href="//category.vip.com/search-1-0-1.html?q=3|29882||&amp;rp=30069|30000&amp;ff=home|0|4|3" target="_blank">照片墙</a><a href="//category.vip.com/search-1-0-1.html?q=3|29883||&amp;rp=30069|30000&amp;ff=home|0|4|4" target="_blank">装饰画</a><a href="//category.vip.com/search-1-0-1.html?q=3|29750||&amp;rp=30069|30000&amp;ff=home|0|4|5" target="_blank">摆件</a><a href="//category.vip.com/search-1-0-1.html?q=3|29885||&amp;rp=30069|30000&amp;ff=home|0|4|6" target="_blank">装饰用品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29879||&amp;rp=30069|30000&amp;ff=home|0|4|7" target="_blank">花瓶花艺</a><a href="//category.vip.com/search-1-0-1.html?q=3|29748||&amp;rp=30069|30000&amp;ff=home|0|4|8" target="_blank">建材家装</a><a href="//category.vip.com/search-1-0-1.html?q=3|29884||&amp;rp=30069|30000&amp;ff=home|0|4|9" target="_blank">装饰布艺</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|30002|&amp;rp=30069|0&amp;ff=home|0|5|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/144/aef1148d-471c-4b9d-8920-ccaab0df5862.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/144/aef1148d-471c-4b9d-8920-ccaab0df5862.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|30002|&amp;rp=30069|0&amp;ff=home|0|5|0" target="_blank">生活用品</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|29891||&amp;rp=30069|30002&amp;ff=home|0|5|1" target="_blank">收纳用品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29901||&amp;rp=30069|30002&amp;ff=home|0|5|2" target="_blank">水具</a><a href="//category.vip.com/search-1-0-1.html?q=3|37684||&amp;rp=30069|30002&amp;ff=home|0|5|3" target="_blank">水杯茶具</a><a href="//category.vip.com/search-1-0-1.html?q=3|29889||&amp;rp=30069|30002&amp;ff=home|0|5|4" target="_blank">浴室用品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29886||&amp;rp=30069|30002&amp;ff=home|0|5|5" target="_blank">日化用品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29890||&amp;rp=30069|30002&amp;ff=home|0|5|6" target="_blank">清洁用品</a><a href="//category.vip.com/search-1-0-1.html?q=3|29888||&amp;rp=30069|30002&amp;ff=home|0|5|7" target="_blank">伞</a><a href="//category.vip.com/search-1-0-1.html?q=3|29701||&amp;rp=30069|30002&amp;ff=home|0|5|8" target="_blank">酒具</a><a href="//category.vip.com/search-1-0-1.html?q=3|78342||&amp;rp=30069|30002&amp;ff=home|0|5|9" target="_blank">保温杯</a><a href="//category.vip.com/search-1-0-1.html?q=3|78461||&amp;rp=30069|30002&amp;ff=home|0|5|10" target="_blank">洗衣液</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cate-second-temp">
                            <a class="cate-img blink-img" href="//category.vip.com/search-1-0-1.html?q=2|29998|&amp;rp=30069|0&amp;ff=home|0|6|0" target="_blank"><img data-original="//a.vpimg4.com/upload/category/2016/06/27/0/4ae56e5e-1531-420d-a4b5-c3c0e0575597.jpg" src="//a.vpimg4.com/upload/category/2016/06/27/0/4ae56e5e-1531-420d-a4b5-c3c0e0575597.jpg" class="" height="80" width="70" style="display: inline;"></a>
                            <div class="cate-list-fix">
                                <div class="cate-list-title"><a class="ani" href="//category.vip.com/search-1-0-1.html?q=2|29998|&amp;rp=30069|0&amp;ff=home|0|6|0" target="_blank">五金建材</a></div>
                                <div class="cate-list-mores">
                                    <div class="cate-fix">
                                        <a href="//category.vip.com/search-1-0-1.html?q=3|37678||&amp;rp=30069|29998&amp;ff=home|0|6|1" target="_blank">浴霸</a><a href="//category.vip.com/search-1-0-1.html?q=3|37675||&amp;rp=30069|29998&amp;ff=home|0|6|2" target="_blank">台灯</a><a href="//category.vip.com/search-1-0-1.html?q=3|29856||&amp;rp=30069|29998&amp;ff=home|0|6|3" target="_blank">卫浴</a><a href="//category.vip.com/search-1-0-1.html?q=3|37674||&amp;rp=30069|29998&amp;ff=home|0|6|4" target="_blank">落地灯</a><a href="//category.vip.com/search-1-0-1.html?q=3|37672||&amp;rp=30069|29998&amp;ff=home|0|6|5" target="_blank">龙头</a><a href="//category.vip.com/search-1-0-1.html?q=3|37676||&amp;rp=30069|29998&amp;ff=home|0|6|6" target="_blank">氛围灯</a><a href="//category.vip.com/search-1-0-1.html?q=3|29855||&amp;rp=30069|29998&amp;ff=home|0|6|7" target="_blank">厨房建材</a><a href="//category.vip.com/search-1-0-1.html?q=3|37677||&amp;rp=30069|29998&amp;ff=home|0|6|8" target="_blank">LED灯</a><a href="//category.vip.com/search-1-0-1.html?q=3|37673||&amp;rp=30069|29998&amp;ff=home|0|6|9" target="_blank">吊顶灯</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="cate-hot-brand cate-hot-brand6">
                        <div class="cate-hot-title">热门品牌</div>
                        <div class="slide" data-key="home"><div class="slide-list" style="height: 630px;">

        <div class="slide-item clearfix" style="display: block;">

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000008&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/09/27/35/f05e68ba-8997-44c6-a38d-ca8369a1825b.png" src="//a.vpimg3.com/upload/brandcool/0/2016/09/27/35/f05e68ba-8997-44c6-a38d-ca8369a1825b.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="7°C银匠世家"><span class="slide-item-text">7°C银匠世家</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000045&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2015/08/19/72/67a5e661-1865-4b34-8b04-711bf086bca4.png" src="//a.vpimg3.com/upload/brandcool/0/2015/08/19/72/67a5e661-1865-4b34-8b04-711bf086bca4.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="DE VERLI"><span class="slide-item-text">DE VERLI</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000057&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/23/48/e7317bfc-e1ff-4b87-86bb-5e9903cb57a1.png" src="//a.vpimg3.com/upload/brandcool/0/2016/08/23/48/e7317bfc-e1ff-4b87-86bb-5e9903cb57a1.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="ELLE"><span class="slide-item-text">ELLE</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000058&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201306/2013062709570186750.jpg" src="//a.vpimg3.com/upload/brandcool/201306/2013062709570186750.jpg" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="ELLE HOMME"><span class="slide-item-text">ELLE HOMME</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000065&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/30/71/572440ed-2c5f-4d18-bf58-a5d500304ecd.png" src="//a.vpimg3.com/upload/brandcool/0/2016/08/30/71/572440ed-2c5f-4d18-bf58-a5d500304ecd.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="ESPRIT"><span class="slide-item-text">ESPRIT</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000067&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201304/2013042611401772370.jpg" src="//a.vpimg3.com/upload/brandcool/201304/2013042611401772370.jpg" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="EVERGREEN"><span class="slide-item-text">EVERGREEN</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000122&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2015/06/02/1/17b2e294-f366-43a5-86db-01769265f31e.png" src="//a.vpimg3.com/upload/brandcool/0/2015/06/02/1/17b2e294-f366-43a5-86db-01769265f31e.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="KOBOLD"><span class="slide-item-text">KOBOLD</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000124&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2015/09/06/168/5d0b4db4-ab8e-4d15-a543-5f8b9bcbb857.png" src="//a.vpimg3.com/upload/brandcool/0/2015/09/06/168/5d0b4db4-ab8e-4d15-a543-5f8b9bcbb857.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="LA CLOVER"><span class="slide-item-text">LA CLOVER</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000195&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201309/2013092417324327070.jpg" src="//a.vpimg3.com/upload/brandcool/201309/2013092417324327070.jpg" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="TOD\'S"><span class="slide-item-text">TOD\'S</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000222&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2017/02/28/150/8340fadd-d6c7-4edd-bcbb-cc755a3f6b1f.png" src="//a.vpimg3.com/upload/brandcool/0/2017/02/28/150/8340fadd-d6c7-4edd-bcbb-cc755a3f6b1f.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="莎莎苏"><span class="slide-item-text">莎莎苏</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000248&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/10/83/2024bda3-96e0-4b83-afba-65250e15408e.png" src="//a.vpimg3.com/upload/brandcool/0/2016/08/10/83/2024bda3-96e0-4b83-afba-65250e15408e.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="爱帝"><span class="slide-item-text">爱帝</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000254&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/31/100/9994cabd-e8c1-4a10-9b97-bc69f91f2e3e.png" src="//a.vpimg3.com/upload/brandcool/0/2016/08/31/100/9994cabd-e8c1-4a10-9b97-bc69f91f2e3e.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="爱美丽"><span class="slide-item-text">爱美丽</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000256&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/04/05/144/d27c3201-29ef-42b6-aa61-e4e4bf24a844.png" src="//a.vpimg3.com/upload/brandcool/0/2016/04/05/144/d27c3201-29ef-42b6-aa61-e4e4bf24a844.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="爱慕"><span class="slide-item-text">爱慕</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000264&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/03/25/149/36b34675-fe75-4eca-bff8-880a317f48fe.png" src="//a.vpimg3.com/upload/brandcool/0/2016/03/25/149/36b34675-fe75-4eca-bff8-880a317f48fe.png" class="" style="display: inline;">
                <span class="ani slide-item-cover" title="安莉芳"><span class="slide-item-text">安莉芳</span></span>
            </a>

        </div>

        <div class="slide-item clearfix" style="display: none;">

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000265&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/22/9/1158d75e-e3fe-4088-9764-adb9072614bc.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="安玛莉"><span class="slide-item-text">安玛莉</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000280&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/11/15/43/d457ef84-b02d-4b89-9840-851deb74d944.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="芭比"><span class="slide-item-text">芭比</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000306&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/09/09/172/07b2916e-bce3-4a8f-a710-9e2f69ce20f9.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="宝缦"><span class="slide-item-text">宝缦</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000318&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/04/05/61/1ff26b1a-2bd9-4116-8429-9269121be9d4.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="倍轻松"><span class="slide-item-text">倍轻松</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000335&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/04/05/157/84055281-bafd-4582-ad03-bb9457a3c06b.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="博洋"><span class="slide-item-text">博洋</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000346&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2017/01/20/172/bf9b780c-dc7e-4a07-9e9c-40f5965bdd85.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="黛安芬"><span class="slide-item-text">黛安芬</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000349&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/09/191/897ed3ef-a693-4629-a132-45ac3b06829e.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="丹尼熊"><span class="slide-item-text">丹尼熊</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000363&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2014/08/22/55/201408221408707510_2238.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="迪士尼"><span class="slide-item-text">迪士尼</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000372&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201304/2013042616314034920.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="多样屋"><span class="slide-item-text">多样屋</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000380&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/29/40/cb96e6a5-b513-4b2c-8607-51696b7e2ca7.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="法罗"><span class="slide-item-text">法罗</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000386&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/04/05/118/b8fc79b2-2a74-4ac3-a7fa-1ac34e4a0c9e.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="飞利浦"><span class="slide-item-text">飞利浦</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000394&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/03/25/15/61e15cb7-845d-4423-9e4b-21e8d8aaf5d8.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="芬狄诗"><span class="slide-item-text">芬狄诗</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000401&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201307/2013071010442030130.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="富安娜"><span class="slide-item-text">富安娜</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000438&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201311/2013112711302555370.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="花雨伞"><span class="slide-item-text">花雨伞</span></span>
            </a>

        </div>

        <div class="slide-item clearfix" style="display: none;">

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000442&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201307/2013070117363417840.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="皇冠"><span class="slide-item-text">皇冠</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000444&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201309/2013091117465216360.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="吉列"><span class="slide-item-text">吉列</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000456&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2015/06/04/94/15ec9742-6fc8-4571-bb4e-7bb8f435f8cb.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="接吻猫"><span class="slide-item-text">接吻猫</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000466&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/22/141/9e2fbe71-5279-465f-8c8d-ace7b1e549bc.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="金利来"><span class="slide-item-text">金利来</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000472&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/0/upload/brandcool/2016/04/05/120/505679f8-f2e1-41f1-845d-e7d8fe6a7477.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="九阳"><span class="slide-item-text">九阳</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000474&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2014/08/22/86/201408221408707595_6441.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="玖姿"><span class="slide-item-text">玖姿</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000478&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/11/52/9461e34d-a5fc-46de-8b2f-ec210a60673f.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="卡丹路"><span class="slide-item-text">卡丹路</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000479&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2015/03/05/58/caaf4602-12bf-4f7d-86ee-8f6772a3ff30.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="卡迪娜"><span class="slide-item-text">卡迪娜</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000481&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/11/26/6c689316-07c5-4d46-8cb7-79df18a6d3d8.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="卡帝乐鳄鱼"><span class="slide-item-text">卡帝乐鳄鱼</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000488&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201305/2013052214460479380.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="卡骆驰"><span class="slide-item-text">卡骆驰</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000494&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2014/10/21/57/201410211413858754_3299.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="卡文"><span class="slide-item-text">卡文</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000500&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/08/08/3/11f7cfa7-8c44-4513-ba11-341d7c9ddb46.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="康莉"><span class="slide-item-text">康莉</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000501&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/0/2016/03/14/125/1e779c4b-3545-4a50-adfa-7da5bcd95117.png" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="康妮雅"><span class="slide-item-text">康妮雅</span></span>
            </a>

            <a href="//category.vip.com/search-1-0-1.html?q=1|30069|10000506&amp;rp=30069|0&amp;f=hotBrand" target="_blank">
                <img data-original="//a.vpimg3.com/upload/brandcool/201305/2013050910065019670.jpg" src="//s2.vipstatic.com/img/share/blank.png" class="lazy">
                <span class="ani slide-item-cover" title="恐龙"><span class="slide-item-text">恐龙</span></span>
            </a>

        </div>

    </div>
    <div class="cate-page"><span class="active">•</span><span>•</span><span>•</span></div></div>
                        <div id="home-banner" class="cate-right-banner"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>';
        //http://dev-cbd.com/shopwap/datacaiji/jindon.html
        $data = array(
            'cat_url'    => $page,
            'caiji_rule' => array(
                'cat1_name' => array("#home .cate-second-temp .cate-list-title a",'text'),
                'href1_link' => array("#home .cate-second-temp .cate-list-title a",'href'),
                'cat2_html'  => array("#home .cate-second-temp .cate-list-mores",'html'),
            ),
        );
        $service  = D('Caiji','Service');
        $cat_data = $service->getJindong($data);

        ppd($cat_data);
    }
}