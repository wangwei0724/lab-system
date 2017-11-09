var grid = {
    db: {
        sig_type: [{id:0,name:'all'}],
        loadData: function(filter) {
            var self = this;
            return grid.getData(filter.pageIndex,filter.pageSize, filter.sig_class_name).then(function(data){
                
                self.sig_type = [{id:0,name:'all'}]
                var index = 1;
                for( var i in data.sig_type){
                    self.sig_type.push({
                        id: index++,
                        name: data.sig_type[i]
                    })
                }
                $("#jsgrid").jsGrid("fieldOption", "sig_class_name", "items", self.sig_type);
                $('.jsgrid-grid-header select').val(filter.sig_class_name);
                return {
                        data: data.data,
                        itemsCount: data.count
                    };
            },function(err) {
                return []
            })
        }
    },
    initGrid: function(){
        var db = this.db;
        jsGrid.locale("zh-cn");
        $("#jsgrid").jsGrid({
            width: "100%",
            //height: "400px",

            inserting: false,
            editing: false,
            sorting: false,
            paging: true,
            filtering:true, 
            autoload: true,
            controller: db,
            pageSize: 25,
            pageLoading:true,

            fields: [
                { name: "cid", type: "text", title:'序号', width: 25, filtering:false},   
                { name: "class", type: "text", title:'一级攻击类型', filtering:false, align: 'center'},  
                { name: "sig_class_name", type: "select", title:'二级攻击类型', items: db.sig_type, valueField: "name", textField: "name"},
                { name: "ip_src", type: "text", title:'源ip地址', filtering:false, align: 'center'},
                { name: "ip_dst", type: "text", title:'目的ip地址', filtering:false, align: 'center'},
                
                { name: "timestamp", type: "text", title:'时间', width: 80, filtering:false},
            ]
        });
    },
    getData: function(pgIndex, pgSize, cName){

        return new Promise(function(resolve,reject){
            $.ajax({
                url: '/lab-system/api/attack.php?pgSize='+pgSize+'&pgIndex='+pgIndex+'&class='+cName,
                beforeSend: function(){
                    $('.loadEffect').show();
                },
                success: function(data){
                    $('.loadEffect').hide();
                    data = JSON.parse(data);
                   
                    if(data.status === 0){
                        resolve(data)
                    } else {
                        reject(data.status)
                    }
                },
                error: function(){
                    $('.loadEffect').hide();
                    reject(-1)
                }
            })
        })
    }
}

grid.initGrid();