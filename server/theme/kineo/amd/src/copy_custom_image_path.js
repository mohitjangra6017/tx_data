/**
 * @copyright City & Guilds Kineo 2021
 * @author Simon Adams <simon.adams@kineo.com>
 */
define([], function () {
        return {
            init: function () {
                const table = document.querySelector('.custom-image-table');
                if (!table) {
                    return;
                }

                table.addEventListener('click', function (event) {
                    if (event.target.className !== 'custom-image-path') {
                        return;
                    }

                    document.querySelectorAll('.custom-image-path').forEach(function (element) {
                            element.classList.remove('on-click-highlight');
                        }
                    );
                    event.target.classList.add('on-click-highlight');

                    var text = event.target.textContent;
                    if (navigator && navigator.clipboard) {
                        navigator.clipboard.writeText(text);
                        return;
                    }

                    // IE polyfill
                    var el = document.createElement('textarea');
                    el.value = event.target.textContent;
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);
                });
            }
        };
    }
);