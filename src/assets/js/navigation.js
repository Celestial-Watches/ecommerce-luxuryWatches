// window.history.replaceState:
// The function checks if the replaceState method is available in the window.history API (which is supported by most modern browsers).
// If the method exists, it replaces the current state in the browser's history with a new state.

// replaceState(null, null, window.location.href):
// null, null: These represent the state object and title. No state is being passed, and the title remains unchanged.
// window.location.href: The URL remains the same, but it prevents form data from being re-sent if the user refreshes the page.

if(window.history.replaceState){
    window.history.replaceState( null, null, window.location.href);
}