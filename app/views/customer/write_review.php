<?php include '../app/views/partials/header.php'; ?>

<h2>Write Review for Booking #<?php echo $booking['booking_id']; ?></h2>
<form action="?url=review/store" method="POST">
    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
    <div class="form-group">
        <label>Rating</label>
        <select name="rating" required>
            <option value="">Select rating</option>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Very Good</option>
            <option value="3">3 - Good</option>
            <option value="2">2 - Fair</option>
            <option value="1">1 - Poor</option>
        </select>
    </div>
    <div class="form-group">
        <label>Comment</label>
        <textarea name="comment" rows="4"></textarea>
    </div>
    <button type="submit" class="btn">Submit Review</button>
</form>

<?php include '../app/views/partials/footer.php'; ?>