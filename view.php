<?php
$view = "";
if (isset($_GET['RouteName']))
    $view = $_GET['RouteName'];

$pin = -1;
if (isset($_GET['PinCode']))
    $pin = strval($_GET['PinCode']);

$vgt = isset($_GET['VGT']);
$filter = isset($_GET['f']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
</head>

<body style="margin: 0;">
    <div id="map" style="height: 100vh;"></div>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Disable accidental text selection
        document.onselectstart = function() {
            return false
        };

        var map = L.map('map');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="http://www.osm.org">OpenStreetMap</a>'
        }).addTo(map);

        /* -----------= Custom Icons =----------- */
        let herbergIcon = L.icon({
            iconUrl: 'https://www.svgrepo.com/show/39715/bed.svg',
            iconSize: [30, 30], // width and height of the image in pixels
            iconAnchor: [15, 15], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -15] // point from which the popup should open relative to the iconAnchor
        })

        let beheerderIcon = L.icon({
            iconUrl: 'https://www.svgrepo.com/show/52135/room-service.svg',
            iconSize: [30, 30], // width and height of the image in pixels
            iconAnchor: [15, 15], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -15] // point from which the popup should open relative to the iconAnchor
        })

        let donkeyIcon = L.icon({
            iconUrl: 'ezel met huifkar.png',
            iconSize: [50, 50], // width and height of the image in pixels
            iconAnchor: [20, 40], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -30] // point from which the popup should open relative to the iconAnchor
        })
        /* -------------------------------------- */

        <?php
        if ($pin != -1) {
            $splitPin = explode(",", strval($pin));
            if (count($splitPin) == 2 && intval($splitPin[1]) >= 0) { ?>
                $.getJSON("./api/markers.json?pin=<?php echo $pin ?>", function(data) {
                    if (!data.coordinates) return;
                    var marker = L.marker(data.coordinates, {
                        icon: donkeyIcon
                    }).addTo(map).bindPopup('Ezel locatie');
                });
        
        <?php }} ?>

        <?php if (!empty($view)) { ?>
            var gpx = './gpx/<?php echo $view ?>.gpx'; // URL to your GPX file or the GPX itself
            $.ajax({
                url: gpx,
                type: 'HEAD',
                error: function() {
                    map.fitWorld();
                },
                success: function() {
                    new L.GPX(gpx, {
                        async: true,
                        marker_options: {
                            startIconUrl: 'https://raw.githubusercontent.com/mpetazzoni/leaflet-gpx/main/pin-icon-start.png',
                            endIconUrl: 'https://raw.githubusercontent.com/mpetazzoni/leaflet-gpx/main/pin-icon-end.png',
                            shadowUrl: 'https://raw.githubusercontent.com/mpetazzoni/leaflet-gpx/main/pin-shadow.png',
                            wptIcons: {
                                '': donkeyIcon //'ezel met huifkar.png'
                            }
                        },
                        polyline_options: {
                            color: 'blue',
                            opacity: 0.75,
                            weight: 2,
                            lineCap: 'round',
                            lineJoin: 'arcs',
                            dashArray: '4'
                        }
                    }).on('loaded', function(e) {
                        map.fitBounds(e.target.getBounds());
                    }).addTo(map);
                }
            });
        <?php } ?>

        function createCustomIcon(feature, latlng) {
            if (feature.properties && feature.properties.marker_symbol) {
                switch (feature.properties.marker_symbol) {
                    case "beheerder":
                        return L.marker(latlng, {
                            icon: beheerderIcon
                        });
                    case "hostel":
                        return L.marker(latlng, {
                            icon: herbergIcon
                        });
                }
            }
            return L.marker(latlng);
        }

        function onEachFeature(feature, layer) {
            if (feature.properties) {
                var popupContent = '';
                if (feature.properties.name)
                    popupContent += '<p><b>' + feature.properties.name + '</b></p>';

                if (feature.properties.popupContent)
                    popupContent += '<p>' + feature.properties.popupContent + '</p>';

                layer.bindPopup(popupContent);
            }
        }

        $.getJSON("./api/markers.json", function(data) {
            <?php if ($filter) echo "var filter =" ?>L.geoJSON(data, {
                onEachFeature: onEachFeature,
                pointToLayer: createCustomIcon,
                <?php if ($filter) {
                    $filter = $_GET['f'];
                    $filter = explode(",", $filter);
                    if (count($filter) != 2) return;
                ?>
                    filter: function(feature, layer) {
                        return (feature.properties.id == <?php echo intval($filter[1]) ?> && feature.properties.type == "<?php echo $filter[0] ?>");
                    }
                <?php } ?>
            }).addTo(map);
            <?php if ($filter) echo "map.fitBounds(filter.getBounds()); map.setZoom(10);" ?>
        });
    </script>
</body>

</html>