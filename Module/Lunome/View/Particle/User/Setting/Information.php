<?php $vars = get_defined_vars(); ?>
<?php $account = $vars['account']; ?>
<form action="/?module=lunome&action=user/setting/sns" method="post" class="form-horizontal">
<div class="col-md-9 thumbnail">
    <h5>个人信息更新</h5>
    <hr>
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">QQ</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">手机</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">生日</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">性别</label>
        <div class="col-sm-10">
            <select class="form-control">
                <option></option>
                <option>男</option>
                <option>女</option>
                <option>其他</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">性取向</label>
        <div class="col-sm-10">
            <select class="form-control">
                <option></option>
                <option>异性</option>
                <option>同行</option>
                <option>双性</option>
                <option>无性</option>
                <option>二禁</option>
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">情感状态</label>
        <div class="col-sm-10">
        <!-- 
        单身  Single
热恋中 In a relationship
已订婚 Engaged
已婚 Married
同居
很难说 It's complicated
已婚分居 Separated
离异 Divorced
寡妇 Widowed
其他
         -->
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-10">
          <input type="text" class="form-control">
        </div>
    </div>
    
    
    
    <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Sign in</button>
    </div>
  </div>
<!--     
    <div class="form-group">
        <label>：</label>
        
    </div>
    
    <div class="form-group">
        <label>：</label>
        <select class="form-control">
        </select>
    </div>
    
    <div class="form-group">
        <label>：</label>
        <select class="form-control">
        </select>
    </div>
    
    <div class="form-group">
        <label>居住地：</label>
        <div class="clearfix">
            <div class="col-md-4">
                <input type="text" class="form-control" >
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" >
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" >
            </div>
        </div>
    </div>
    -->
</div>
</form>