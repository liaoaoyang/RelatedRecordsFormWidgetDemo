# OctoberCMS 相关记录表单组件DEMO

## 概述

为 [OctoberCMS](http://octobercms.com/) 编写的相关记录选择表单组件。

## 应用场景

如blog中的相关blog。

## 实现简述

在数据库中通过JSON数组的格式存储相关记录的id，前端存在一个隐藏的input标签：

```
<div class="input-group">
    <input
        type="text"
        name="<?php echo $name ?>"
        id="<?php echo $this->getId() ?>"
        value="<?php echo json_encode($value); ?>"
        placeholder=""
        class="form-control"
        autocomplete="off"
        style="display: none;"
    >
</div>
```

编辑时，根据用户操作生成id数组，生成对应的JSON数组，作为input的值，随同原有表单提交。

## 使用效果

通过弹出的Modal窗口添加关联：

![select related records](https://github.com/liaoaoyang/RelatedRecordsFormWidgetDemo/blob/master/images/select_records.png?raw=true)

选择后可供调整顺序或移除：

![selected](https://github.com/liaoaoyang/RelatedRecordsFormWidgetDemo/blob/master/images/selected.png?raw=true)
