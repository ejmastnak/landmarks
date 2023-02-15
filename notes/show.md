Docs: 

About: creating `show` LandmarkController method and Show Landmarks page.

```php
<?php
// In LandmarkController
public function show(Landmark $landmark)
{
    return inertia('Landmarks/Show', [
        'landmark' => $landmark,
    ]);
}
```

Then the barebones Show page is just

```vue
<template>
    <h1>{{landmark.name}}</h1>

    <ul>
        <li>Name: {{landmark.name}}</li>
        <li>Landmark type: {{landmark.type}}</li>
        <li>City: {{landmark.city}}</li>
        <li>Country: {{landmark.country}}</li>
        <li v-if="landmark.comment">Comments: {{landmark.comment}}</li>
    </ul> 

</template>

<script>
export default {
    props: {
        landmark: Object,
    }
};
</script>
```
