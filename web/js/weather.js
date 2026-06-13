document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('weatherForm');
    const input = document.getElementById('cityInput');
    const result = document.getElementById('weatherResult');

    if (form && input && result) {
        form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const city = input.value.trim();
        if (!city) {
            result.className = 'weather-result error-card';
            result.textContent = 'Введите название города.';
            return;
        }

        result.className = 'weather-result muted-card loading';
        result.textContent = 'Загружаю погоду...';

        try {
            const response = await fetch(`/api/weather?city=${encodeURIComponent(city)}`);
            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Ошибка получения погоды.');
            }

            const data = payload.data;
            const location = data.location;
            const current = data.current;

            result.className = 'weather-result success-card';
            result.innerHTML = `
                <div class="result-top">
                    <div>
                        <span class="result-label">${escapeHtml(location.country || '')}</span>
                        <h2>${escapeHtml(location.name || city)}</h2>
                        <p>${escapeHtml(current.weather_description || 'Нет описания')}</p>
                    </div>
                    <div class="result-temp">${escapeHtml(String(current.temperature ?? '—'))}°C</div>
                </div>
                <div class="result-grid">
                    <div><strong>${escapeHtml(String(current.apparent_temperature ?? '—'))}°C</strong><span>Ощущается</span></div>
                    <div><strong>${escapeHtml(String(current.humidity ?? '—'))}%</strong><span>Влажность</span></div>
                    <div><strong>${escapeHtml(String(current.wind_speed ?? '—'))} км/ч</strong><span>Ветер</span></div>
                    <div><strong>${escapeHtml(location.timezone || 'auto')}</strong><span>Часовой пояс</span></div>
                </div>
                <a class="history-link" href="/history">Открыть историю запросов →</a>
            `;
        } catch (error) {
            result.className = 'weather-result error-card';
            result.textContent = error.message;
        }
        });
    }

    const apiForm = document.getElementById('apiTesterForm');
    const apiInput = document.getElementById('apiCityInput');
    const apiStatus = document.getElementById('apiTesterStatus');
    const apiOutput = document.getElementById('apiJsonOutput');
    const apiRequestUrl = document.getElementById('apiRequestUrl');

    if (apiInput && apiRequestUrl) {
        apiInput.addEventListener('input', () => {
            apiRequestUrl.textContent = `/api/weather?city=${encodeURIComponent(apiInput.value.trim())}`;
        });
    }

    if (apiForm && apiInput && apiStatus && apiOutput) {
        apiForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            const city = apiInput.value.trim();
            if (!city) {
                apiStatus.className = 'weather-result error-card';
                apiStatus.textContent = 'Введите название города.';
                return;
            }

            const url = `/api/weather?city=${encodeURIComponent(city)}`;
            if (apiRequestUrl) {
                apiRequestUrl.textContent = url;
            }

            apiStatus.className = 'weather-result muted-card loading';
            apiStatus.textContent = 'Выполняю запрос к API...';
            apiOutput.textContent = '{\n  "loading": true\n}';

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const payload = await response.json();
                apiOutput.textContent = JSON.stringify(payload, null, 2);

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'API вернул ошибку.');
                }

                const location = payload.data.location || {};
                const current = payload.data.current || {};

                apiStatus.className = 'weather-result success-card';
                apiStatus.innerHTML = `
                    <div class="result-top">
                        <div>
                            <span class="result-label">HTTP ${response.status}</span>
                            <h2>${escapeHtml(location.name || city)}</h2>
                            <p>${escapeHtml(location.country || 'Страна не указана')} · ${escapeHtml(current.weather_description || 'Описание не найдено')}</p>
                        </div>
                        <div class="result-temp">${escapeHtml(String(current.temperature ?? '—'))}°C</div>
                    </div>
                    <a class="history-link" href="/history">Проверить запись в истории запросов →</a>
                `;
            } catch (error) {
                apiStatus.className = 'weather-result error-card';
                apiStatus.textContent = error.message;
            }
        });
    }

});

function escapeHtml(value) {
    return value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}
