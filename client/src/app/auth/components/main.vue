<template>

	<div class='landing-wrapper'>

	  <div class='ocean'>
	  	<div class="wave"></div>
	  	<div class="wave"></div>
	  </div>

	  <div class="container">
	    <header class="hero">
	      <h1 class='heading1'>Wa<span class='highlight'>vv</span>y Notes</h1>
	      <h3 class='heading3'>Pretty good notes app tbh</h3>
	    </header>

	    <el-card class='adjust-width auth-prompt'>
	      <router-view></router-view>
	    </el-card>

	  </div>

	</div>

</template>

<style scoped lang='sass'>

.landing-wrapper
	height: 100%
	.ocean
		height: 5px
		width: 100%
		position: fixed
		bottom: 0
		left: 0
		background: #409EFF
		z-index: -1
		.wave 
			background: url(~assets/svg/wave.svg) repeat-x
			position: absolute
			top: -198px
			width: 6400px
			height: 198px
			animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) infinite
			transform: translate3d(0, 0, 0)
			&:nth-of-type(2)
				top: -175px
				animation: wave 7s cubic-bezier( 0.36, 0.45, 0.63, 0.53) -.125s infinite, swell 7s ease -1.25s infinite
				opacity: 1
	.container
		display: flex
		align-items: center
		height: 100%
		.hero
			.heading1
				font-size: 3.6rem
				letter-spacing: .8px
				margin-bottom: 1.6rem
			.heading3
				font-size: 1.4rem
				font-weight: 400
				line-height: 3.2rem
				border-left: 4px solid #409EFF
				padding-left: 1.6rem
		.highlight
			color: #409EFF

@keyframes wave 
	0% 
		margin-left: 0
	100% 
		margin-left: -1600px

@keyframes swell
	0%, 100%
		transform: translate3d(0,-25px,0)
	50% 
		transform: translate3d(0,5px,0)
  

</style>
