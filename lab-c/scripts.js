class WeatherApp {
  constructor(apiKey, resultsBlockSelector) {
    this.apiKey = apiKey;
    this.resultsBlock = document.querySelector(resultsBlockSelector);
    this.currentWeatherLink = `https://api.openweathermap.org/data/2.5/weather?q={query}&appid=${apiKey}&units=metric&lang=pl`;
    this.forecastLink = `https://api.openweathermap.org/data/2.5/forecast?q={query}&appid=${apiKey}&units=metric&lang=pl`;
    this.currentWeather = undefined;
    this.forecast = undefined;
  }

  getCurrentWeather(query) {
    let url = this.currentWeatherLink.replace('{query}', query);
    let req = new XMLHttpRequest();
    req.open("GET", url, true);
    req.addEventListener("load", () => {
      this.currentWeather = JSON.parse(req.responseText);

      this.drawWeather();

    });
    req.send();
  }


  getForecast(query) {
    let url  = this.forecastLink.replace('{query}', query);
    fetch(url, {})
      .then((response) => {
        return response.json();
      })
      .then((data) => {
        this.forecast = data.list;
        console.log(this.forecast);
        this.drawWeather();
      })
    ;
  }

  getWeather(query) {
    this.getCurrentWeather(query);
    this.getForecast(query);

  }



  drawWeather(data, isForecast, title) {
    //Ja używam innych ikonek, więc musze je zmapowac, tak aby działały z tym wywołaniem API
    const icons = {
      "01d": "wi-day-sunny",
      "01n": "wi-night-clear",
      "02d": "wi-day-cloudy",
      "02n": "wi-night-alt-cloudy",
      "03d": "wi-cloud",
      "03n": "wi-cloud",
      "04d": "wi-cloudy",
      "04n": "wi-cloudy",
      "09d": "wi-showers",
      "09n": "wi-showers",
      "10d": "wi-day-rain",
      "10n": "wi-night-alt-rain",
      "11d": "wi-thunderstorm",
      "11n": "wi-thunderstorm",
      "13d": "wi-snow",
      "13n": "wi-snow",
      "50d": "wi-fog",
      "50n": "wi-fog"
    };

    const currentWeatherBlock = this.resultsBlock.querySelector('#current-weather');
    const forecastBlock = this.resultsBlock.querySelector('#forecast');
    currentWeatherBlock.innerHTML = '';
    forecastBlock.innerHTML = '';
    if(this.currentWeather) {
      const date =  new Date(this.currentWeather.dt * 1000);
      const iconName = icons[this.currentWeather.weather[0].icon] ?? "wi-na";
      const weatherBlock = this.createWeatherBlock(
        `${date.toLocaleDateString("pl-PL")} ${date.toLocaleTimeString("pl-PL")}`,
        this.currentWeather.main.temp,
        this.currentWeather.main.feels_like,
        iconName,
        this.currentWeather.weather[0].description
      );

      currentWeatherBlock.appendChild(weatherBlock);
    }
    if(this.forecast) {
      for(let i = 0; i < this.forecast.length; i++){
        let weather = this.forecast[i];
        const date =  new Date(weather.dt * 1000);
        const iconName = icons[weather.weather[0].icon] ?? "wi-na";
        const weatherBlock = this.createWeatherBlock(
          `${date.toLocaleDateString("pl-PL")} ${date.toLocaleTimeString("pl-PL")}`,
          weather.main.temp,
          weather.main.feels_like,
          iconName,
          weather.weather[0].description
        );
        forecastBlock.appendChild(weatherBlock);
      }
    }
  }

  createWeatherBlock(dateString, temperature, feelsLikeTemperature, iconName, description) {


    const weatherBlock = document.createElement('div');
    weatherBlock.className = "weather-block";


    const dateBlock = document.createElement('div');
    dateBlock.className = "weather-date";
    dateBlock.innerHTML = dateString;
    weatherBlock.appendChild(dateBlock);


    const temperatureBlock = document.createElement('div');
    temperatureBlock.className = "weather-temperature";
    temperatureBlock.innerHTML = `${temperature} &deg;C`;
    weatherBlock.appendChild(temperatureBlock);

    const feelsLikeBlock = document.createElement('div');
    feelsLikeBlock.className = "weather-temperature-feels-like";
    feelsLikeBlock.innerHTML = `Odczuwalna: ${feelsLikeTemperature} &deg;C`;
    weatherBlock.appendChild(feelsLikeBlock);

    const iconImg = document.createElement('i');
    iconImg.className = `weather-icon wi ${iconName}`;
    weatherBlock.appendChild(iconImg);

    const descriptionBlock = document.createElement('div');
    descriptionBlock.className = "weather-description";
    descriptionBlock.innerHTML = description;
    weatherBlock.appendChild(descriptionBlock);

    return weatherBlock;
  }
}

document.weatherApp = new WeatherApp('66400328962043326bbdba1277d49c79', '#weather-results-container');
document.querySelector('#checkButton').addEventListener('click', function(){
  const query = document.querySelector('#locationInput').value;
  document.weatherApp.getWeather(query);
});
