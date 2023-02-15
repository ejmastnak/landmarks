About: creating `index` LandmarkController method and Index Landmarks page.

```php
<?php
// In LandmarkController
public function index()
{
  // Simple redirect to Landmarks/Index, passing all Landmark instances
  // as prop `landmarks`.
  return Inertia::render('Landmarks/Index', [
      'landmarks' => Landmark::all(),
  ]);
}
```

Then the barebones Index page is just

```vue
<template>
    <table>
        <thead>
            <tr>
                <th>Country</th>
                <th>Type</th>
                <th>Name</th>
                <th>City</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="landmark in landmarks" :key="landmark.id">
                <td>{{landmark.country}}</td>
                <td>{{landmark.type}}</td>
                <td>{{landmark.name}}</td>
                <td>{{landmark.city}}</td>
                <td>{{landmark.comment}}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ["landmarks"],
};
</script>
```

Link to `Landmarks/Show.vue` with

```vue
<inertia-link :href="'/landmarks/' + landmark.id">{{ item.name }}</inertia-link>
```
