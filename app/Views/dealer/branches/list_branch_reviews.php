<?php
echo view('dealer/includes/_header');
echo view('dealer/includes/_sidebar');
?>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Reviews</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">reviews</li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>

            <div id="reviews-container">
                <?= $reviewsHtml ?>
            </div>

        </div>

        <?php echo view('dealer/includes/_footer'); ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        let isLoaded = false; // Prevent multiple calls

        function loadBranchReviews() {
            if (isLoaded) return; // Prevent reloading if already called
            isLoaded = true;

            console.log('loadBranchReviews called'); // Debugging line
            $.ajax({
                url: "<?= base_url('dealer-branch-reviews') ?>",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    console.log('AJAX success', response); // Debugging line
                    if (response.html) {
                        $('#reviews-container').html(response.html);
                    } else {
                        $('#reviews-container').html('<p>No reviews available.</p>');
                    }
                },
                error: function() {
                    console.log('AJAX error'); // Debugging line
                    $('#reviews-container').html('<p>Error loading reviews. Please try again later.</p>');
                }
            });
        }

        //loadBranchReviews(); // Call the function once
    });
</script>