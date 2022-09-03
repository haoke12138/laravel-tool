<div id="mapContainer" style="width:100%;height: 250px;"></div>
<script>
    function loadMapJScript() {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = '//api.map.baidu.com/api?type=webgl&v=1.0&ak={{ config('admin.map.keys.baidu') }}&callback=init';
        document.body.appendChild(script);
    }
    function init() {
        var map = new BMapGL.Map('mapContainer'); // 创建Map实例
        // var point = new BMapGL.Point(113.946185,22.531059); // 创建点坐标
        var point = new BMapGL.Point('{{ config('map.baidu-x') }}','{{ config('map.baidu-y') }}'); // 创建点坐标
        var marker = new BMapGL.Marker(point);  // 创建标注
        map.centerAndZoom(point, 20);
        map.enableScrollWheelZoom(); // 启用滚轮放大缩小
        map.addOverlay(marker);              // 将标注添加到地图中
        var opts = {
            width : 250,     // 信息窗口宽度
            height: 40,     // 信息窗口高度
            title : "{{ config('app.name') }}" , // 信息窗口标题
        }
        var infoWindow = new BMapGL.InfoWindow("地址：{{ config('footer.address') }}", opts);  // 创建信息窗口对象
        marker.addEventListener("click", function(){
            map.openInfoWindow(infoWindow, point); //开启信息窗口
        });
    }
    window.onload = loadMapJScript; // 异步加载地图
</script>
