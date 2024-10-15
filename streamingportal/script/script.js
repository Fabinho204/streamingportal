function getSelectedFilters() {
  var genre = document.getElementById("genreSelect").value;
  var year = document.getElementById("yearSelect").value;
  var rating = document.getElementById("ratingSelect").value;
  var service = document.getElementById("serviceSelect").value;

  var filterSummary = "";

  if (genre !== "All") {
      filterSummary += "Genre: " + genre + "<br>";
  }

  if (year !== "All") {
      filterSummary += "Year: " + year + "<br>";
  }

  if (rating !== "All") {
      filterSummary += "Rating: " + rating + "<br>";
  }

  if (service !== "All") {
      filterSummary += "Streaming Service: " + service + "<br>";
  }

  if (!filterSummary) {
      return "No Filters Selected";
  }

  return filterSummary;
}

function confirmDelete(movieTitle, movieId) {
  Swal.fire({
    title: "Are you sure?",
    text: "This action cannot be undone! Deleting the movie: " + movieTitle,
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#009999",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete movie!"
  }).then((result) => {
    if (result.isConfirmed) {
      // If confirmed, submit the hidden form associated with the movie
      document.getElementById('deleteForm_' + movieId).submit();
    }
  });
}

function toggleWatchlist(movieId) {
    var button = document.getElementById("watchlist-btn-" + movieId);
    
    var action = button.innerHTML.trim() === "Add Watchlist" ? 'add' : 'remove';
    var ajaxUrl = action === 'add' ? '../elements/add_to_watchlist.php' : '../elements/remove_from_watchlist.php';
    
    $.ajax({
        url: ajaxUrl,
        method: 'POST',
        data: { offers_id: movieId },
        success: function(response) {
            const data = JSON.parse(response);
            if (data.success) {
                // Only update button text after the request was successful
                if (action === 'add') {
                    button.innerHTML = "Remove Watchlist";
                    Swal.fire({
                        title: 'Added!',
                        text: 'The movie has been added to your watchlist.',
                        icon: 'success',
                        confirmButtonColor: "#009999",
                        confirmButtonText: 'OK'
                    });
                } else {
                    button.innerHTML = "Add Watchlist";
                    Swal.fire({
                        title: 'Removed!',
                        text: 'The movie has been removed from your watchlist.',
                        icon: 'success',
                        confirmButtonColor: "#009999",
                        confirmButtonText: 'OK'
                    });
                }
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: "#009999",
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: "#009999",
                confirmButtonText: 'OK'
            });
        }
    });
}
