// Smooth Scrolling for Navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Fade-in effect on Mission Section Image
window.addEventListener('scroll', function() {
    const missionImage = document.querySelector('.mission img');
    const imagePosition = missionImage.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;
    if (imagePosition < windowHeight - 100) {
        missionImage.classList.add('fade-in');
    }
});

// Slide-in effect for Team Members
window.addEventListener('scroll', function() {
    const teamMembers = document.querySelectorAll('.team-member');
    teamMembers.forEach(member => {
        const memberPosition = member.getBoundingClientRect().top;
        if (memberPosition < window.innerHeight - 100) {
            member.classList.add('slide-in');
        }
    });
});
