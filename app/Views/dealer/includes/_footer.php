<div class="footer-wrap pd-20 mb-20 card-box">
    Copyright @2024 - All Rights Reserved by WheelPact | Product by <a href="https://www.parastoneglobal.com" target="_blank">Parastone Global</a>
</div>
<!-- js -->
<script>
    var base_url = "<?= base_url('') ?>";
</script>

<script src="<?php echo base_url(); ?>assets/vendors/scripts/core.js"></script>

<script src="<?php echo base_url(); ?>assets/vendors/scripts/script.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/scripts/layout-settings.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/scripts/dashboard.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/apexcharts/apexcharts.min.js"></script>

<!-- DataTables JS -->
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>

<!-- DataTables Buttons Extension -->
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>assets/src/plugins/datatables/js/buttons.print.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


<script src="<?php echo base_url(); ?>assets/src/plugins/fancybox/dist/jquery.fancybox.min.js"></script>

<!-- Sweetalert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<!-- USER DEFINED JS -->
<script src="<?php echo base_url(); ?>assets/vendors/scripts/dealer.js"></script>
<!-- JQUERY STEPS JS -->
<script src="<?php echo base_url(); ?>assets/src/plugins/jquery-steps/jquery.steps.js"></script>


<!-- Google Maps JavaScript -->
<script>
    // Get the current URL path and split it into segments
    var pathArray = window.location.pathname.split('/').filter(segment => segment !== '');

    if (pathArray[0] === 'edit-branch' || 'add-branch') {

        let map;
        let marker;
        let geocoder;
        let infoWindow;

        // Function to initialize the map with the given latitude and longitude
        function initMapWithCoordinates(lat, lng) {
            // Initialize the map
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: lat,
                    lng: lng
                },
                zoom: 13,
            });

            // Initialize geocoder and info window
            geocoder = new google.maps.Geocoder();
            infoWindow = new google.maps.InfoWindow();

            // Set up autocomplete for the input field
            const input = document.getElementById("location-input");
            const autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo("bounds", map);

            // Listener for place selection from autocomplete
            autocomplete.addListener("place_changed", () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                const address = place.formatted_address || "Selected Location";
                updateMarkerAndMap(place.geometry.location.lat(), place.geometry.location.lng(), address);
            });

            // Listener for map click to place marker
            map.addListener("click", (event) => {
                const lat = event.latLng.lat();
                const lng = event.latLng.lng();
                geocodeLatLng(lat, lng, true); // Show address after click
            });

            // Set an initial marker
            geocodeLatLng(lat, lng, false); // Fetch the initial address without showing it immediately
        }

        // Function to update the marker position and map center
        function updateMarkerAndMap(lat, lng, address) {
            // Clear existing marker
            if (marker) marker.setMap(null);

            // Add a new marker
            marker = new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng
                },
                map: map,
            });

            // Update map center
            map.setCenter({
                lat: lat,
                lng: lng
            });

            // Add event listeners for click and hover to show the address
            marker.addListener("click", () => {
                infoWindow.setContent(address);
                infoWindow.open(map, marker);
            });

            marker.addListener("mouseover", () => {
                infoWindow.setContent(address);
                infoWindow.open(map, marker);
            });

            marker.addListener("mouseout", () => {
                infoWindow.close();
            });

            // Update hidden fields
            document.getElementById("map_latitude").value = lat;
            document.getElementById("map_longitude").value = lng;
        }

        // Function to geocode latitude and longitude to get address details
        function geocodeLatLng(lat, lng, showInfoWindow = false) {
            const latlng = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            geocoder.geocode({
                location: latlng
            }, (results, status) => {
                if (status === "OK") {
                    if (results[0]) {
                        const address = results[0].formatted_address;
                        let city = "";
                        let district = "";
                        let state = "";

                        results[0].address_components.forEach((component) => {
                            const types = component.types;
                            if (types.includes("locality")) {
                                city = component.long_name; // City
                            }
                            if (types.includes("administrative_area_level_2")) {
                                district = component.long_name; // District
                            }
                            if (types.includes("administrative_area_level_1")) {
                                state = component.long_name; // State
                            }
                        });

                        // Update hidden fields for city, district, and state
                        document.getElementById("map_city").value = city;
                        document.getElementById("map_district").value = district;
                        document.getElementById("map_state").value = state;

                        // Update the marker and optionally show the address
                        updateMarkerAndMap(lat, lng, address);
                        if (showInfoWindow) {
                            infoWindow.setContent(address);
                            infoWindow.open(map, marker);
                        }
                    } else {
                        window.alert("No results found");
                    }
                } else {
                    window.alert("Geocoder failed due to: " + status);
                }
            });
        }


    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDB9PZ68Q3UoU3Fc1qzyfLnXB3kFFMU9U&v=beta&callback=initMap&libraries=places&v=weekly" async defer></script>

</body>

</html>