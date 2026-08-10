const r = document.documentElement,
    t = document.getElementById('theme'),
    m = document.getElementById('menu'),
    mobile = document.getElementById('mobile');


// ==========================================
// THEME
// ==========================================

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

    t.innerHTML = x === 'dark' ? '☀' : '☾';
}

t.onclick = () => {
    setTheme(
        r.dataset.theme === 'dark'
            ? 'light'
            : 'dark'
    );
};


// ==========================================
// MOBILE MENU
// ==========================================

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


// ==========================================
// REVEAL ANIMATION
// ==========================================

const o = new IntersectionObserver(
    es => es.forEach(e => {

        if (e.isIntersecting) {
            e.target.classList.add('visible');
            o.unobserve(e.target);
        }

    }),
    {
        threshold: 0.08
    }
);

document.querySelectorAll('.reveal').forEach(e => {
    o.observe(e);
});


// ==========================================
// CURRENT YEAR
// ==========================================

document.getElementById('year').textContent =
    new Date().getFullYear();


// ==========================================
// CONTACT FORM
// ==========================================

const f = document.getElementById('contactForm');
const s = document.getElementById('status');
const b = document.getElementById('submit');

if (f) {

    f.onsubmit = e => {

        e.preventDefault();

        // Check HTML validation
        if (!f.checkValidity()) {
            f.reportValidity();
            return;
        }

        // Get form values
        const name =
            document.getElementById('name').value.trim();

        const email =
            document.getElementById('email').value.trim();

        const subject =
            document.getElementById('subject').value.trim();

        const message =
            document.getElementById('message').value.trim();


        // ======================================
        // BASIC VALIDATION
        // ======================================

        if (!name || !email || !subject || !message) {

            s.textContent =
                'Please fill in all fields.';

            s.style.color = '#dc2626';

            return;
        }


        // ======================================
        // YOUR EMAIL ADDRESS
        // ======================================

        const recipient =
            'abhishektripathi0205@gmail.com';


        // ======================================
        // CREATE EMAIL SUBJECT
        // ======================================

        const emailSubject =
            encodeURIComponent(
                'Portfolio Contact: ' + subject
            );


        // ======================================
        // CREATE EMAIL BODY
        // ======================================

        const emailBody =
            encodeURIComponent(
`Hello Abhishek,

You have received a new message from your portfolio website.

--------------------------------
CONTACT DETAILS
--------------------------------

Name: ${name}

Email: ${email}

Subject: ${subject}

Message:
${message}

--------------------------------
Sent from Abhishek Tripathi Portfolio
--------------------------------`
            );


        // ======================================
        // CREATE MAILTO LINK
        // ======================================

        const mailtoLink =
            `mailto:${recipient}?subject=${emailSubject}&body=${emailBody}`;


        // ======================================
        // BUTTON STATE
        // ======================================

        b.disabled = true;
        b.textContent = 'Opening Email...';


        // ======================================
        // OPEN EMAIL APPLICATION
        // ======================================

        window.location.href = mailtoLink;


        // ======================================
        // SHOW STATUS
        // ======================================

        s.textContent =
            'Opening your email application...';

        s.style.color = 'var(--success)';


        // Restore button
        setTimeout(() => {

            b.disabled = false;
            b.textContent = 'Send Message →';

        }, 1500);
    };
}
