var grid = {
    db: {
        loadData: function(filter) {
                return $.grep(this.attacks, function(data) {
                    return (filter.sig_class_name=='all' || data.sig_class_name === filter.sig_class_name)
                });
            }
        },
    init: function() {
        var self = this;
        this.getData().then(function(data){
            self.db.attacks = data.data
            self.db.sig_type = [{id:0,name:'all'}]
            var index = 1;
            for( var i in data.sig_type){
                self.db.sig_type.push({
                    id: index++,
                    name: data.sig_type[i]
                })
            }
            console.log(self.db.sig_type)
            self.initGrid()
        },function(err) {
            self.db.attacks = []
            self.db.sig_type = []
            self.initGrid()
        })
        
    },
    initGrid: function(){
        var db = this.db;
        jsGrid.locale("zh-cn");
        $("#jsgrid").jsGrid({
            width: "100%",
            //height: "400px",

            inserting: false,
            editing: false,
            sorting: true,
            paging: true,
            filtering:true, 
            autoload: true,
            controller: db,
            pgSize: 15,

            fields: [
                { name: "cid", type: "text", title:'序号', width: 25, filtering:false},     
                { name: "sig_class_name", type: "select", title:'攻击类型', items: db.sig_type, valueField: "name", textField: "name"},
                { name: "sig_class_name", type: "text", title:'攻击详情', filtering:false, align: 'center'},
                { name: "timestamp", type: "text", title:'时间', width: 40, filtering:false},
            ]
        });
    },
    getData: function(){

        return new Promise(function(resolve,reject){
            $.ajax({
                url: '/lab-system/api/attack.php',
                type: 'Get',
                success: function(data){
                    data = JSON.parse(data);
                    console.log(data)
                    if(data.status === 0){
                        resolve(data)
                    } else {
                        reject(data.status)
                    }
                },
                error: function(){
                    reject(-1)
                }
            })
        })
    }
}

grid.init();