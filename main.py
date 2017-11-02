#!/usr/local/bin/python2.7
# -*- coding: utf-8 -*-  
import urllib  
import urllib2  
import re  
import json
import MySQLdb
import thread
import time
import threading
import tp


def getHtml(url):           #定义getHtml()函数，用来获取页面源代码
    page = urllib.urlopen(url)    #urlopen()根据url来获取页面源代码
    html = page.read()           #从获取的对象中读取内容
    return html

def log(data):
    fp = open("log.log",'a')
    fp.write(data + '\n')
    fp.close()
    

def getBlogs(html):
    p = re.compile(r'''<li class="f-single f-s-s".+?</li>''')
    return p.findall(html)
    	
def getImage(html):  #定义getImage()函数，用来获取图片地址并下载
    reg = r'\.jpg'   #定义匹配图片地址的url的正则表达式
    imgre = re.compile(reg, re.I |re.M )   #对正则表达式进行编译，运行效率更高
    imagelist = imgre.findall(html)#使用findall()查找html中匹配正则表达式的图片url
    return imagelist

def delHtmlFlag(strHtml):
    if(strHtml[0] != '<'):
        strHtml = '<' + strHtml
    p = re.compile(r'</?\w+[^>]*>')
    return p.sub('', strHtml)

def getImageCount(strHtml):
    p = re.compile(r'class="img-num".+?</a>')
    m = p.search(strHtml)
    if(m != None):
        return delHtmlFlag(m.group())
    else:
        return 0

def getTopicId(strHtml):
    p1 = re.compile(r'data-topicid[^<]+?data-pickey')
    p2 = re.compile(r'".+?"')
    s1 = s2 = ''
    m1 = p1.search(strHtml)
    if(m1):
        s1 = m1.group()
    else:
        return;
    m2 = p2.search(s1)
    if(m2):
        s2 = m2.group()
    return  s2[1: -1];

def getImageList(qqNum, topicId, urlPhotoJson):
    page = urllib.urlopen(urlPhotoJson.replace('varqqNum',qqNum).replace('vartopicId',topicId))    #urlopen()根据url来获取页面源代码
    html = page.read()           #从获取的对象中读取内容
    p = re.compile(r'''http.+?\\/b\\/.+?"''')
    arrResult = p.findall(html)
    for i in range(0, len(arrResult) -1):
        arrResult[i] = arrResult[i].replace('''\/''','/')[0:-1]
    return arrResult
     

def test(strTest):
    print strTest

#创建线程池
pool = tp.ThreadPool(40)

def downloadImages(arrImages):
    for i in range(0, len(arrImages) -1):
        url = arrImages[i]
        path = "./imgs"+url[url.rfind('/'):]
        path = path[0:path.find('&')].replace('*', '-') + '.jpg'
        #添加工作线程
        pool.add_task(saveImage, (url, path))
    pool.start_workers()
    pool.wait()


def saveImage(url, path):
    print 'begin:  '+ path + '\n'
    f = urllib2.urlopen(url) 
    data = f.read() 
    with open(path, "wb") as code:     
        code.write(data)
    f.close()
    print 'downloaded:  '+ path + '\n'

        

        
def getTitle(strHtml):
    p1 = re.compile(r'ui-mr10 c_tx">.+?</a>')
    p2 = re.compile(r'<h4 class="txt-box-title">.+?</h4>')
    s1 = s2 = ''
    m1 = p1.search(strHtml)
    if(m1):
        s1 = delHtmlFlag(m1.group())
    m2 = p2.search(strHtml)
    if(m2):
        s2 = delHtmlFlag(m2.group())
    return  s1 + ',' + s2

def delNum(strTitle):
    p = re.compile(r'\d{1,6}号')
    return p.sub('', strTitle)

def getYear(strTitle):
    p = re.compile(r'\d{2}年')
    m = p.search(strTitle)
    if(m):
        return m.group()
    else:
        return 0;

def createBrandRe():
    try:
        conn=MySQLdb.connect(host='42.96.204.119',user='admin',passwd='sql6yhn',db='jx',port=3306 , charset="utf8")
        cur=conn.cursor()
        cur.execute('select brand_name from Brand group by brand_name')
        arrBrands = cur.fetchall()
        cur.close()
        conn.close()
    except MySQLdb.Error,e:
         print "Mysql Error %d: %s" % (e.args[0], e.args[1])
    reStrNum = '(\d){1,4}';
    reStr = '';
    for brand in arrBrands:
        reStr += brand[0] + reStrNum + '|'
    return reStr

def getBrand(strTitle):
    p = re.compile(reBrandStr)
    m = p.search(strTitle)
    if(m):
        return m.group()
    else:
        return '';

def getPrice(strTitle):
    p = re.compile(r'(\d|\.){1,5}万')
    m = p.search(strTitle)
    if(m):
        return m.group()
    else:
        return '';

def getByQQ(qqNum):
    
    html = getHtml('http://ic2.s21.qzone.qq.com/cgi-bin/feeds/feeds_html_module?i_uin=' + qqNum)
    
    arrResult = getBlogs(html)
    
    for i in range(0, len(arrResult) -1):
        if(int(getImageCount(arrResult[i])) < 10):
            continue
        #arrResult[i] = delHtmlFlag(arrResult[i])
        topicId = getTopicId(arrResult[i])
        
        downloadImages(getImageList(qqNum, topicId, 'http://shplist.photo.qzone.qq.com/fcgi-bin/cgi_list_photo?hostUin=varqqNum&topicId=vartopicId&uin=0&pageStart=0&pageNum=30&inCharset=utf-8&outCharset=utf-8&format=json'))
        strTitle = getTitle(arrResult[i])
        strTitle = delNum(strTitle)
        log( str(getYear(strTitle)) + str(getPrice(strTitle)) + '： ' + strTitle)



reBrandStr = createBrandRe()
pirnt reBrandStr

if __name__ == '__main__':

    qqNum = '2492268485'
    getByQQ(qqNum)
