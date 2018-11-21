<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>入学登记表</title>

  <link rel="stylesheet" type="text/css" href="css/styles.css" media="all">
  <link rel="stylesheet" href="css/add.css">
  <script src="js/jquery.js"></script>
  <script type="text/javascript" src="js/moment.js"></script>
  <script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
  <script>
    $(function () {
      $(".date").datetimepicker({
        locale: "zh-cn",
        format: "YYYY-MM-DD",
        dayViewHeaderFormat: "YYYY年 MMMM"
      });
      $('.add').click(function () {
       var str=`
        <div id="add_or">
          <p class="m_20">其他联系人：如果都无法联系到以上人员，作为备选联系人</p>
          <div class="input_box">
            <label for="">亲属关系 
            </label>
            <select class="sel" value="男" dir="rtl" name="sex">
              <option value="爸爸">爸爸</option>
              <option value="妈妈">妈妈</option>
              <option value="奶奶">奶奶</option>
              <option value="爷爷">爷爷</option>
              <option value="外公">外公</option>
              <option value="外婆">外婆</option>
              <option value="姨妈">姨妈</option>
              <option value="姨父">姨父</option>
              <option value="姑妈">姑妈</option>
              <option value="姑父">姑父</option>
              <option value="舅舅">舅舅</option>
              <option value="舅妈">舅妈</option>
              <option value="叔叔">叔叔</option>
              <option value="婶婶">婶婶</option>
            </select>
          </div>
          <div class="input_box">
            <label for="">手机号码 
            </label>
            <input type="text" required placeholder="请输入手机号码">
          </div>
        </div>
       `  
      if($('.form').attr('isAppend')=='true'){
        $('#add_else').append(str)
       $('.form').attr('isAppend',false)
      }
      })
    })
  </script>
</head>

<body>
  <nav>
    <div class="content">
      <div class="call">各位家长：</div>
      <div class="content_detail">
        我校目前已经使用趣儿网平台进行信息化管理，为了 保障学生的在校情况，同时便于接受学校的通知、活 动、作业等消息，请您尽快完善学生的基础信息以及 家庭成员信息
      </div>
      <div class="child">
        万达幼儿园
      </div>
    </div>
  </nav>
  <form action="" isAppend='true' class="form" method="post">
    <div class="form_title">
      <h1>学生信息&nbsp;&nbsp;(必填)</h1>
      <div class="input_box">
        <label for="">学生姓名
          <i>*</i>
        </label>
        <input type="text" required placeholder="请输入学生的姓名">
      </div>
      <div class="input_box">
        <label for="">性别
          <i>*</i>
        </label>
        <select class="sel" value="男" name="sex">
          <option value="男">男</option>
          <option value="女">女</option>
        </select>
      </div>
      <div class="input_box">
        <label for="">生日
          <i>*</i>
        </label>
        <input type="text" class="date" required placeholder="选择生日">
      </div>
      <div class="input_box">
        <label for="">就读班级</label>
        <input type="text" placeholder="香蕉(芒果)">
      </div>
    </div>
    <div class="form_title">
      <h1 class="m_20">家庭联系成员</h1>
      <p>第一联系人：学校第一优先联系
        <i>必填</i>
      </p>
      <div class="input_box">
        <label for="">亲属关系
          <i>*</i>
        </label>
        <select class="sel" value="男" dir="rtl" name="sex">
          <option value="爸爸">爸爸</option>
          <option value="妈妈">妈妈</option>
          <option value="奶奶">奶奶</option>
          <option value="爷爷">爷爷</option>
          <option value="外公">外公</option>
          <option value="外婆">外婆</option>
          <option value="姨妈">姨妈</option>
          <option value="姨父">姨父</option>
          <option value="姑妈">姑妈</option>
          <option value="姑父">姑父</option>
          <option value="舅舅">舅舅</option>
          <option value="舅妈">舅妈</option>
          <option value="叔叔">叔叔</option>
          <option value="婶婶">婶婶</option>
        </select>
      </div>
      <div class="input_box">
        <label for="">手机号码
          <i>*</i>
        </label>
        <input type="text" required placeholder="请输入手机号码">
      </div>
    </div>
    <div class="form_title">
      <p class="m_20">第二联系人：学校第二优先联系 </p>
      <div class="input_box">
        <label for="">亲属关系
          <i>*</i>
        </label>
        <select class="sel" value="男" dir="rtl" name="sex">
          <option value="爸爸">爸爸</option>
          <option value="妈妈">妈妈</option>
          <option value="奶奶">奶奶</option>
          <option value="爷爷">爷爷</option>
          <option value="外公">外公</option>
          <option value="外婆">外婆</option>
          <option value="姨妈">姨妈</option>
          <option value="姨父">姨父</option>
          <option value="姑妈">姑妈</option>
          <option value="姑父">姑父</option>
          <option value="舅舅">舅舅</option>
          <option value="舅妈">舅妈</option>
          <option value="叔叔">叔叔</option>
          <option value="婶婶">婶婶</option>
        </select>
      </div>
      <div class="input_box">
        <label for="">手机号码
          <i>*</i>
        </label>
        <input type="text" required placeholder="请输入手机号码">
      </div>
    </div>
    <div class="form_title" id="add_else" id="else">
      <div id="add_or">
        <p class="m_20">其他联系人：如果都无法联系到以上人员，作为备选联系人</p>
        <div class="input_box">
          <label for="">亲属关系 
          </label>
          <select class="sel" value="男" dir="rtl" name="sex">
            <option value="爸爸">爸爸</option>
            <option value="妈妈">妈妈</option>
            <option value="奶奶">奶奶</option>
            <option value="爷爷">爷爷</option>
            <option value="外公">外公</option>
            <option value="外婆">外婆</option>
            <option value="姨妈">姨妈</option>
            <option value="姨父">姨父</option>
            <option value="姑妈">姑妈</option>
            <option value="姑父">姑父</option>
            <option value="舅舅">舅舅</option>
            <option value="舅妈">舅妈</option>
            <option value="叔叔">叔叔</option>
            <option value="婶婶">婶婶</option>
          </select>
        </div>
        <div class="input_box">
          <label for="">手机号码 
          </label>
          <input type="text" required placeholder="请输入手机号码">
        </div>
      </div>
    </div>
    <div class="add">
      <img src="./images/add.png" alt="" srcset="">
      <p>继续添加其他联系人</p>
    </div>
    <button type="submit">加入学校</button>
  </form>
</body>

</html>