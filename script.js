// انتظر حتى يتم تحميل DOM بالكامل
document.addEventListener('DOMContentLoaded', function () {
    // تبديل اللغة
    const switchLangButton = document.getElementById('switch-lang');
    if (switchLangButton) {
        switchLangButton.addEventListener('click', function () {
            const htmlElement = document.querySelector('html');
            const langText = document.getElementById('lang-text');
            const langFlag = document.getElementById('lang-flag');

            if (htmlElement.getAttribute('lang') === 'ar') {
                htmlElement.setAttribute('lang', 'en');
                htmlElement.setAttribute('dir', 'ltr');
                langText.textContent = 'EN';
                langFlag.src = '/images/usa.png';
                langFlag.alt = 'English';

                langFlag.onerror = function () {
                    console.error('Error loading USA flag image. Check the file path.');
                };

                document.querySelectorAll('[data-lang="ar"]').forEach(element => {
                    element.textContent = element.getAttribute('data-en');
                });
            } else {
                htmlElement.setAttribute('lang', 'ar');
                htmlElement.setAttribute('dir', 'rtl');
                langText.textContent = 'ع';
                langFlag.src = '/images/uae.png';
                langFlag.alt = 'العربية';

                document.querySelectorAll('[data-lang="ar"]').forEach(element => {
                    element.textContent = element.getAttribute('data-ar');
                });
            }
        });
    }

    // زر العودة إلى الأعلى
    const backToTopButton = document.createElement('button');
    backToTopButton.innerText = '↑';
    backToTopButton.style.position = 'fixed';
    backToTopButton.style.bottom = '20px';
    backToTopButton.style.right = '20px';
    backToTopButton.style.padding = '10px';
    backToTopButton.style.backgroundColor = '#e60000';
    backToTopButton.style.color = 'white';
    backToTopButton.style.border = 'none';
    backToTopButton.style.cursor = 'pointer';
    backToTopButton.style.display = 'none';
    document.body.appendChild(backToTopButton);

    window.addEventListener('scroll', () => {
        backToTopButton.style.display = window.scrollY > 200 ? 'block' : 'none';
    });

    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // البحث والاقتراحات
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            const suggestions = document.getElementById('search-suggestions');
            if (query.length > 2) {
                suggestions.innerHTML = '<p>اقتراح 1</p><p>اقتراح 2</p>';
                suggestions.style.display = 'block';
            } else {
                suggestions.style.display = 'none';
            }
        });
    } else {
        console.error('عنصر البحث غير موجود');
    }

    // تبديل الصور المصغرة في صفحة تفاصيل المنتج
    document.querySelectorAll('.thumbnails img').forEach(thumbnail => {
        thumbnail.addEventListener('click', function () {
            const mainImage = document.querySelector('.product-images img');
            if (mainImage) {
                mainImage.src = this.src;
            }
        });
    });

    // إزالة العنصر من السلة
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function () {
            const cartItem = this.closest('.cart-item');
            if (cartItem) {
                cartItem.remove();
                updateCartTotal();
            }
        });
    });

    // تحديث الإجمالي في صفحة السلة
    function updateCartTotal() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const priceElement = item.querySelector('p');
            const quantityInput = item.querySelector('input');
            if (priceElement && quantityInput) {
                const price = parseFloat(priceElement.textContent.replace(' د.إ', ''));
                const quantity = parseInt(quantityInput.value);
                total += price * quantity;
            }
        });

        const cartSummary = document.querySelector('.cart-summary p');
        if (cartSummary) {
            cartSummary.textContent = `الإجمالي: ${total.toFixed(2)} د.إ`;
        }
    }

    // تحديث الكمية في السلة
    document.querySelectorAll('.quantity input').forEach(input => {
        input.addEventListener('change', updateCartTotal);
    });

    // تأكيد الطلب في صفحة الدفع
    const checkoutForm = document.querySelector('.checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();
            alert('تم تأكيد الطلب بنجاح!');
        });
    }

    // فتح وإغلاق النافذة المنبثقة للدفع
    const paymentPopup = document.getElementById('payment-popup');
    if (paymentPopup) {
        document.getElementById('open-payment-popup').addEventListener('click', openPaymentPopup);
        document.getElementById('close-payment-popup').addEventListener('click', closePaymentPopup);
    }

    function openPaymentPopup() {
        if (paymentPopup) {
            paymentPopup.style.display = 'flex';
        }
    }

    function closePaymentPopup() {
        if (paymentPopup) {
            paymentPopup.style.display = 'none';
        }
    }

    // تبديل ظهور معلومات بطاقة الائتمان
    const paymentMethod = document.getElementById('payment-method');
    if (paymentMethod) {
        paymentMethod.addEventListener('change', togglePaymentDetails);
    }

    function togglePaymentDetails() {
        const creditCardDetails = document.getElementById('credit-card-details');
        if (creditCardDetails) {
            const cardFields = creditCardDetails.querySelectorAll('input');
            if (paymentMethod.value === 'credit-card') {
                creditCardDetails.style.display = 'block';
                cardFields.forEach(field => field.setAttribute('required', 'true'));
            } else {
                creditCardDetails.style.display = 'none';
                cardFields.forEach(field => field.removeAttribute('required'));
            }
        }
    }

    // عرض رسالة تأكيد الطلب
    const confirmationMessage = document.getElementById('confirmation-message');
    if (confirmationMessage) {
        const form = document.getElementById('checkout-form');
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!form.checkValidity()) {
                    alert('يرجى ملء جميع الحقول المطلوبة.');
                    return;
                }
                confirmationMessage.style.display = 'block';
                setTimeout(() => {
                    confirmationMessage.style.display = 'none';
                }, 5000);
                setTimeout(() => {
                    closePaymentPopup();
                }, 500);
            });
        }
    }

    // تسجيل المستخدم
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const formData = new FormData();
            formData.append('username', username);
            formData.append('email', email);
            formData.append('password', password);

            fetch('test.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء إرسال البيانات. يرجى المحاولة مرة أخرى.');
                });
        });
    }
});

// جلب بيانات المسؤولين وعرضها فقط في صفحة الإدارة
function fetchAdmins() {
    const isAdminPage = window.location.pathname.includes('admin-panel');  // تحقق إذا كانت الصفحة صفحة الإدارة

    if (isAdminPage) {
        fetch('get_admin.php')
            .then(response => response.json())
            .then(admins => {
                const adminList = document.getElementById('admin-list');
                if (!adminList) {
                    console.error('Error: Element #admin-list not found');
                    return; // توقف إذا العنصر غير موجود
                }

                // قم بمعالجة البيانات هنا (عرض المسؤولين)
            })
            .catch(error => {
                console.error('Error fetching admins:', error);
            });
    }
}

// تحميل البيانات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', fetchAdmins);

// دالة لتعديل المسؤول
function startEditAdmin(id) {
    if (!id) {
        console.error("Error: ID is undefined");
        return;
    }

    let row = document.querySelector(`tr[data-id='${id}']`);
    if (!row) {
        console.error(`Error: Row with data-id='${id}' not found`);
        return;
    }

    let nameCell = row ? row.querySelector(".name-cell") : null;
    let emailCell = row ? row.querySelector(".email-cell") : null;

    if (nameCell && emailCell) {
        nameCell.innerHTML = `<input type="text" value="${nameCell.textContent}">`;
        emailCell.innerHTML = `<input type="text" value="${emailCell.textContent}">`;
        row.querySelector(".edit-btn").disabled = true;
        row.querySelector(".delete-btn").disabled = true;
        row.querySelector(".save-btn").style.display = "inline-block";
    }
}
