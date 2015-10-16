<!DOCTYPE html>
<html>
<head>
  <title>API document v1</title>
</head>
<body>

  <h1>API document v1</h1>
  <ul>
    <li><a href="#h1">get all researches</a></li>
    <li><a href="#h2">get researched by researcher arg.</a></li>
  </ul>

  <div style="background-color:#E1F5FE;">
    <h2 id="h1">get all researches</h2>
    <p>
      return all researches in JSON format.
    </p>
    <p>
      url : <a href="researches.php">get</a>
    </p>
  </div>

  <div style="background-color:#E1F5FE;">
    <h2 id="h2">get researched by researcher arg.</h2>
    <p>
      return all researches which match author name in JSON format.
    </p>
    <p>
      <b>Parameter</b>
      <dl>
        <dt>name</dt>
        <dd>(string) author name</dd>
      </dl>
    </p>
    <p>
      url : <a href="researches.php?name=antony">get</a>
    </p>
  </div>

</body>
</html>
