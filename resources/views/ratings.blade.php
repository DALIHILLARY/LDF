<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paravet->surname }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Other CSS and meta tags -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $paravet->surname }}</h1>
        <p>Average Rating:
            @php
                $averageRating = $paravet->averageRating(); // Assuming this method calculates the average rating
                $fullStars = floor($averageRating);
                $halfStar = ceil($averageRating - $fullStars);
            @endphp

            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= $fullStars)
                    <i class="fas fa-star" style="color: gold;"></i>
                @elseif ($halfStar > 0 && $i == ($fullStars + 1))
                    <i class="fas fa-star-half-alt" style="color: gold;"></i>
                @else
                    <i class="far fa-star" style="color: gold;"></i>
                @endif
            @endfor
        </p>

        <h2>Ratings</h2>
        @foreach ($ratings as $rating)
            <div class="rating">
                <p>Rating: {{ $rating->rating }}</p>
                <p>Comment: {{ $rating->comment }}</p>
                <p>By: {{ $rating->user->name }}</p>
            </div>
        @endforeach

     
            <h2>Leave a Rating</h2>
            <form action="{{ route('ratings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="paravet_id" value="{{ $paravet->id }}">
                <input type="hidden" name="rating" id="rating" value="0">
                <!-- Star rating interface -->
                <div id="star-rating" data-rating="0">
                    <i class="far fa-star" data-value="1"></i>
                    <i class="far fa-star" data-value="2"></i>
                    <i class="far fa-star" data-value="3"></i>
                    <i class="far fa-star" data-value="4"></i>
                    <i class="far fa-star" data-value="5"></i>
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <textarea name="comment" id="comment" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
      
    </div>

    <script>
    const starRating = document.getElementById('star-rating');
    const ratingInput = document.getElementById('rating');

    starRating.addEventListener('click', (e) => {
        const star = e.target;
        if (star.matches('i')) {
            const value = star.getAttribute('data-value');
            ratingInput.value = value;
            starRating.setAttribute('data-rating', value);
            const stars = starRating.querySelectorAll('i');
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                    s.style.color = 'gold'; // Change star color to gold
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                    s.style.color = ''; // Reset star color to default
                }
            });
        }
    });
</script>

</body>
</html>
