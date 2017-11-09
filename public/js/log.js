var grid = {
    db: {
        loadData: function(filter) {
            var db = this;
            return grid.getData(filter.pageIndex,filter.pageSize).then(function(data){
                return {
                        data: data.data,
                        itemsCount: data.count
                    };
            },function(err) {
                return []
            })
        }
    },
    db2: {
        loadData: function(filter) {
            var db = this;
            return grid.getData2(filter.pageIndex,filter.pageSize).then(function(data){
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
        var db = this.db, db2=this.db2;
        jsGrid.locale("zh-cn");
        $("#jsgrid").jsGrid({
            width: "100%",
            //height: "400px",

            inserting: false,
            editing: false,
            sorting: false,
            paging: true,
            filtering:false, 
            autoload: true,
            controller: db,
            pageSize: 15,
            pageLoading:true,

            fields: [
                { name: "time", type: "text", title:'时间'},   
                { name: "src", type: "text", title:'攻击源'},  
                { name: "dst", type: "text", title:'攻击目标'},
                { name: "type", type: "text", title:'类型'},
                { name: "attampt", type: "text", title:'次数'}
            ]
        });
        $("#jsgrid2").jsGrid({
            width: "100%",
            //height: "400px",

            inserting: false,
            editing: false,
            sorting: false,
            paging: true,
            filtering:false, 
            autoload: true,
            controller: db2,
            pageSize: 15,
            pageLoading:true,

            fields: [
                { name: "time", type: "text", title:'时间'},   
                { name: "src", type: "text", title:'source'},  
                { name: "method", type: "text", title:'方式'},
                { name: "dir", type: "text", title:'目录'},
                { name: "code", type: "text", title:'响应码'},
                { name: "detail", type: "text", title:'详情'},

            ]
        });
    },
    getData: function(pgIndex, pgSize){

        return new Promise(function(resolve,reject){
            $.ajax({
                url: '/lab-system/api/log.php?pgSize='+pgSize+'&pgIndex='+pgIndex,
                
                success: function(data){
                    
                    data = JSON.parse(data);

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
    },
    getData2: function(pgIndex, pgSize){

        return new Promise(function(resolve,reject){
            $.ajax({
                url: '/lab-system/api/access.php?pgSize='+pgSize+'&pgIndex='+pgIndex,
                
                success: function(data){
                    
                    data = JSON.parse(data);

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

grid.initGrid();