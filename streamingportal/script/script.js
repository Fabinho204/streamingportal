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