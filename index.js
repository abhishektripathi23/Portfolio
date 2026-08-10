const r = document.documentElement,
    t = document.getElementById('theme'),
    m = document.getElementById('menu'),
    mobile = document.getElementById('mobile');


// ======================================================
// THEME
// ======================================================

const saved = localStorage.getItem('portfolio-theme');

setTheme(
    saved ||
    (matchMedia('(prefers-color-scheme:dark)').matches
        ? 'dark'
        : 'light')
);

function setTheme(x) {
    r.dataset.theme = x;
    localStorage.setItem('portfolio-theme', x);

    if (t) {
        t.innerHTML = x === 'dark' ? '☀' : '☾';
    }
}

if (t) {
    t.onclick = () => {
        setTheme(
            r.dataset.theme === 'dark'
                ? 'light'
                : 'dark'
        );
    };
}


// ======================================================
// MOBILE MENU
// ======================================================

if (m && mobile) {

    m.onclick = () => {

        mobile.style.display =
            mobile.style.display === 'block'
                ? 'none'
                : 'block';

    };

    document.querySelectorAll('#mobile a').forEach(a => {

        a.onclick = () => {
            mobile.style.display = 'none';
        };

    });
}


// ======================================================
// REVEAL ANIMATION
// ======================================================

const o = new IntersectionObserver(
    es => {

        es.forEach(e => {

            if (e.isIntersecting) {

                e.target.classList.add('visible');

                o.unobserve(e.target);

            }

        });

    },
    {
        threshold: 0.08
    }
);

document.querySelectorAll('.reveal').forEach(e => {
    o.observe(e);
});


// ======================================================
// CURRENT YEAR
// ======================================================

const year = document.getElementById('year');

if (year) {
    year.textContent = new Date().getFullYear();
}


// ======================================================
// CONTACT FORM
// ======================================================
//
// No PHP
// No database
// No API
// No external email service
//
// The recruiter's email application will open with
// the form information already filled in.
//


// Get existing elements from your current HTML

const f = document.getElementById('contactForm'),
    s = document.getElementById('status'),
    b = document.getElementById('submit');


if (f) {

    f.onsubmit = e => {

        e.preventDefault();


        // ----------------------------------------------
        // HTML validation
        // ----------------------------------------------

        if (!f.checkValidity()) {

            f.reportValidity();

            return;

        }


        // ----------------------------------------------
        // Get form fields
        // ----------------------------------------------

        const nameElement =
            document.getElementById('name');

        const emailElement =
            document.getElementById('email');

        const subjectElement =
            document.getElementById('subject');

        const messageElement =
            document.getElementById('message');


        // Make sure the fields exist

        if (
            !nameElement ||
            !emailElement ||
            !subjectElement ||
            !messageElement
        ) {

            if (s) {

                s.textContent =
                    'Contact form fields are not configured correctly.';

                s.style.color = '#dc2626';

            }

            return;

        }


        // ----------------------------------------------
        // Get values
        // ----------------------------------------------

        const name =
            nameElement.value.trim();

        const email =
            emailElement.value.trim();

        const subject =
            subjectElement.value.trim();

        const message =
            messageElement.value.trim();


        // ----------------------------------------------
        // Validate
        // ----------------------------------------------

        if (!name || !email || !subject || !message) {

            if (s) {

                s.textContent =
                    'Please fill in all fields.';

                s.style.color = '#dc2626';

            }

            return;

        }


        // ----------------------------------------------
        // Validate email
        // ----------------------------------------------

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {

            if (s) {

                s.textContent =
                    'Please enter a valid email address.';

                s.style.color = '#dc2626';

            }

            return;

        }


        // ==================================================
        // YOUR EMAIL ADDRESS
        // ==================================================

        const recipient =
            'abhishektripathi0205@gmail.com';


        // ----------------------------------------------
        // Email subject
        // ----------------------------------------------

        const emailSubject =
            encodeURIComponent(
                'Portfolio Contact: ' + subject
            );


        // ----------------------------------------------
        // Email body
        // ----------------------------------------------

        const emailBody =
            encodeURIComponent(
`Hello Abhishek,

You have received a new message from your portfolio website.

========================================
CONTACT DETAILS
========================================

Name:
${name}

Email:
${email}

Subject:
${subject}

Message:
${message}

========================================
Sent from Abhishek Tripathi Portfolio
========================================`
            );


        // ----------------------------------------------
        // Create mailto URL
        // ----------------------------------------------

        const mailtoLink =
            `mailto:${recipient}` +
            `?subject=${emailSubject}` +
            `&body=${emailBody}`;


        // ----------------------------------------------
        // Button state
        // ----------------------------------------------

        if (b) {

            b.disabled = true;

            b.textContent =
                'Opening Email...';

        }


        // ----------------------------------------------
        // Status
        // ----------------------------------------------

        if (s) {

            s.textContent =
                'Opening your email application...';

            s.style.color =
                'var(--success)';

        }


        // ----------------------------------------------
        // Open email application
        // ----------------------------------------------

        window.location.href =
            mailtoLink;


        // ----------------------------------------------
        // Restore button
        // ----------------------------------------------

        setTimeout(() => {

            if (b) {

                b.disabled = false;

                b.textContent =
                    'Send Message →';

            }

        }, 1500);

    };

}
