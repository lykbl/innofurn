<div>
	Scene goes here {{ $this->glb_location }}
{{--	@vite(['resources/js/main.js'])--}}
	<script type="module">
    document.addEventListener('livewire:load', function() {
      import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
      import { Scene } from 'three';

      const loader = new GLTFLoader();

// Optional: Provide a DRACOLoader instance to decode compressed mesh data
// const dracoLoader = new DRACOLoader();
// dracoLoader.setDecoderPath('/examples/jsm/libs/draco/');
// loader.setDRACOLoader(dracoLoader);

      import * as THREE from 'three';

      const scene = new THREE.Scene();
      const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

      console.log('starting')

      const renderer = new THREE.WebGLRenderer();
      renderer.setSize(window.innerWidth, window.innerHeight);
      document.getElementById('scene').appendChild(renderer.domElement);

      const geometry = new THREE.BoxGeometry(1, 1, 1);
      const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
      const cube = new THREE.Mesh(geometry, material);
      scene.add(cube);

      camera.position.z = 5;

      function animate() {
        requestAnimationFrame(animate);

        cube.rotation.x += 0.01;
        cube.rotation.y += 0.01;

        renderer.render(scene, camera);
      }

// Load a glTF resource
      loader.load(
        // resource URL
        'dining_room__kitchen_baked (1)/scene.gltf',
        // called when the resource is loaded
        function(gltf) {

          scene.add(gltf.scene);

          gltf.animations; // Array<THREE.AnimationClip>
          gltf.scene; // THREE.Group
          gltf.scenes; // Array<THREE.Group>
          gltf.cameras; // Array<THREE.Camera>
          gltf.asset; // Object

          console.log(gltf.scene)

          gltf.scene.traverse((child) => {
            console.log(child)
          });
        },
        // called while loading is progressing
        function(xhr) {

          console.log((xhr.loaded / xhr.total * 100) + '% loaded');

        },
        // called when loading has errors
        function(error) {

          console.log('An error happened');

        },
      );

      animate();
    })
	</script>
</div>
