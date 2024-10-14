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

function addToWatchlist(offersId) {
  $.ajax({
      url: '../elements/add_to_watchlist.php', // Path to your backend PHP script
      method: 'POST',
      data: { offers_id: offersId }, // Send the offer (movie/series) ID
      success: function(response) {
          const data = JSON.parse(response);
          if (data.success) {
              Swal.fire({
                  title: 'Added!',
                  text: 'The movie has been added to your watchlist.',
                  icon: 'success',
                  confirmButtonColor: "#009999",
                  confirmButtonText: 'OK'
              });
          } else {
              Swal.fire({
                  title: 'Already Added!',
                  text: 'This movie is already in your watchlist.',
                  icon: 'info',
                  confirmButtonColor: "#009999",
                  confirmButtonText: 'OK'
              });
          }
      },
      error: function() {
          Swal.fire({
              title: 'Error!',
              text: 'An error occurred while adding the movie to your watchlist. Please try again.',
              icon: 'error',
              confirmButtonColor: "#009999",
              confirmButtonText: 'OK'
          });
      }
  });
}


function removeFromWatchlist(offersId) {
  $.ajax({
      url: '../elements/remove_from_watchlist.php', // Path to your backend PHP script
      method: 'POST',
      data: { offers_id: offersId }, // Send the offer (movie/series) ID
      success: function(response) {
          const data = JSON.parse(response);
          if (data.success) {
              Swal.fire({
                  title: 'Removed!',
                  text: 'The movie has been removed from your watchlist.',
                  icon: 'success',
                  confirmButtonColor: "#009999",
                  confirmButtonText: 'OK'
              }).then(() => {
                  location.reload(); // Reload the page after confirmation
              });
          } else {
              Swal.fire({
                  title: 'Error!',
                  text: 'Failed to remove the movie from your watchlist.',
                  icon: 'error',
                  confirmButtonColor: "#009999",
                  confirmButtonText: 'OK'
              });
          }
      },
      error: function() {
          Swal.fire({
              title: 'Error!',
              text: 'An error occurred. Please try again later.',
              icon: 'error',
              confirmButtonColor: "#009999",
              confirmButtonText: 'OK'
          });
      }
  });
}

